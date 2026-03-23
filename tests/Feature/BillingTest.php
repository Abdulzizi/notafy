<?php

namespace Tests\Feature;

use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class BillingTest extends TestCase
{
    use RefreshDatabase;

    private string $serverKey = 'test-server-key';

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('services.midtrans.server_key', $this->serverKey);
    }

    private function midtransPayload(string $orderId = 'INV-STARTER-001', int $amount = 29000, string $email = 'buyer@example.com', string $status = 'settlement'): array
    {
        $grossAmount = number_format($amount, 2, '.', '');
        $statusCode  = '200';

        return [
            'order_id'           => $orderId,
            'status_code'        => $statusCode,
            'gross_amount'       => $grossAmount,
            'transaction_status' => $status,
            'signature_key'      => hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey),
            'customer_details'   => ['email' => $email],
        ];
    }

    public function test_midtrans_webhook_grants_starter_credits(): void
    {
        $user = User::factory()->create([
            'email'   => 'buyer@example.com',
            'credits' => 0,
        ]);

        $this->postJson('/midtrans/webhook', $this->midtransPayload('INV-STARTER-001', 29000));

        $this->assertEquals(200, $user->fresh()->credits);
    }

    public function test_midtrans_webhook_grants_pro_credits(): void
    {
        $user = User::factory()->create([
            'email'   => 'buyer@example.com',
            'credits' => 0,
        ]);

        $this->postJson('/midtrans/webhook', $this->midtransPayload('INV-PRO-001', 99000));

        $this->assertEquals(1000, $user->fresh()->credits);
    }

    public function test_midtrans_webhook_is_idempotent(): void
    {
        $user = User::factory()->create([
            'email'   => 'buyer@example.com',
            'credits' => 0,
        ]);

        $payload = $this->midtransPayload('INV-DUPE-001', 29000);

        $this->postJson('/midtrans/webhook', $payload);
        $this->postJson('/midtrans/webhook', $payload); // replay

        $this->assertEquals(200, $user->fresh()->credits);
        $this->assertDatabaseCount('credit_transactions', 1);
    }

    public function test_midtrans_webhook_rejects_invalid_signature(): void
    {
        $payload = $this->midtransPayload();
        $payload['signature_key'] = 'invalidsignature';

        $response = $this->postJson('/midtrans/webhook', $payload);

        $response->assertStatus(401);
    }

    public function test_midtrans_webhook_ignores_unknown_email(): void
    {
        $response = $this->postJson('/midtrans/webhook',
            $this->midtransPayload('INV-NOUSER-001', 29000, 'nobody@example.com')
        );

        $response->assertStatus(200);
        $this->assertDatabaseCount('credit_transactions', 0);
    }

    public function test_midtrans_webhook_ignores_non_settlement_status(): void
    {
        $user = User::factory()->create([
            'email'   => 'buyer@example.com',
            'credits' => 0,
        ]);

        $this->postJson('/midtrans/webhook', $this->midtransPayload('INV-PENDING-001', 29000, 'buyer@example.com', 'pending'));

        $this->assertEquals(0, $user->fresh()->credits);
    }

    public function test_purchasing_starter_upgrades_plan_to_starter(): void
    {
        $user = User::factory()->create([
            'email'   => 'buyer@example.com',
            'credits' => 0,
            'plan'    => 'free',
        ]);

        $this->postJson('/midtrans/webhook', $this->midtransPayload('INV-STARTER-UP', 29000));

        $this->assertEquals('starter', $user->fresh()->plan);
        $this->assertNotNull($user->fresh()->credits_last_refilled_at);
    }

    public function test_purchased_credits_not_reset_by_weekly_refill(): void
    {
        $user = User::factory()->create([
            'email'   => 'buyer@example.com',
            'credits' => 0,
            'plan'    => 'free',
        ]);

        $this->postJson('/midtrans/webhook', $this->midtransPayload('INV-NORESET-001', 29000));

        $user->refresh()->refillCreditsIfDue();

        $this->assertEquals(200, $user->fresh()->credits);
    }
}
