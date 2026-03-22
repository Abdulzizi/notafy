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

    private function mayarPayload(string $transactionId = 'TXN-001', int $amount = 29000): array
    {
        return [
            'event'  => 'payment.success',
            'id'     => $transactionId,
            'data'   => [
                'id'            => $transactionId,
                'customerEmail' => 'buyer@example.com',
                'amount'        => $amount,
            ],
        ];
    }

    private function signedMayarRequest(array $payload): \Illuminate\Testing\TestResponse
    {
        $secret    = 'test-secret';
        Config::set('services.mayar.webhook_secret', $secret);
        $body      = json_encode($payload);
        $signature = hash_hmac('sha256', $body, $secret);

        return $this->withHeaders(['X-Mayar-Signature' => $signature])
            ->postJson('/mayar/webhook', $payload);
    }

    public function test_mayar_webhook_grants_starter_credits(): void
    {
        $user = User::factory()->create([
            'email'   => 'buyer@example.com',
            'credits' => 0,
        ]);

        $this->signedMayarRequest($this->mayarPayload('TXN-001', 29000));

        $this->assertEquals(200, $user->fresh()->credits);
    }

    public function test_mayar_webhook_grants_pro_credits(): void
    {
        $user = User::factory()->create([
            'email'   => 'buyer@example.com',
            'credits' => 0,
        ]);

        $this->signedMayarRequest($this->mayarPayload('TXN-002', 99000));

        $this->assertEquals(1000, $user->fresh()->credits);
    }

    public function test_mayar_webhook_is_idempotent(): void
    {
        $user = User::factory()->create([
            'email'   => 'buyer@example.com',
            'credits' => 0,
        ]);

        $payload = $this->mayarPayload('TXN-DUPE', 29000);

        $this->signedMayarRequest($payload);
        $this->signedMayarRequest($payload); // replay

        // Credits should only be added once
        $this->assertEquals(200, $user->fresh()->credits);
        $this->assertDatabaseCount('credit_transactions', 1);
    }

    public function test_mayar_webhook_rejects_invalid_signature(): void
    {
        Config::set('services.mayar.webhook_secret', 'real-secret');

        $response = $this->withHeaders(['X-Mayar-Signature' => 'bad-sig'])
            ->postJson('/mayar/webhook', $this->mayarPayload());

        $response->assertStatus(401);
    }

    public function test_mayar_webhook_ignores_unknown_email(): void
    {
        $payload = $this->mayarPayload();
        $payload['data']['customerEmail'] = 'nobody@example.com';

        $response = $this->signedMayarRequest($payload);

        $response->assertStatus(200);
        $this->assertDatabaseCount('credit_transactions', 0);
    }
}
