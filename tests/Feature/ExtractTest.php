<?php

namespace Tests\Feature;

use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExtractTest extends TestCase
{
    use RefreshDatabase;

    public function test_extract_requires_authentication(): void
    {
        $response = $this->postJson('/extract', []);

        $response->assertStatus(401);
    }

    public function test_extract_requires_email_verification(): void
    {
        $user = User::factory()->create(['email_verified_at' => null, 'credits' => 5]);

        $response = $this->actingAs($user)->post('/extract', [
            'receipt' => UploadedFile::fake()->image('receipt.jpg'),
        ]);

        // Unverified users are redirected to verification notice
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_extract_rejects_user_with_no_credits(): void
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'credits' => 0]);

        Storage::fake('local');

        $response = $this->actingAs($user)->post('/extract', [
            'receipt' => UploadedFile::fake()->image('receipt.jpg'),
        ]);

        $response->assertRedirect(route('credits.insufficient'));
        $this->assertEquals(0, $user->fresh()->credits);
    }

    public function test_extract_validates_file_type(): void
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'credits' => 5]);

        Storage::fake('local');

        $response = $this->actingAs($user)->post('/extract', [
            'receipt' => UploadedFile::fake()->create('document.exe', 100, 'application/octet-stream'),
        ]);

        $response->assertSessionHasErrors('receipt');
        // Credits should not be deducted for validation failures
        $this->assertEquals(5, $user->fresh()->credits);
    }

    public function test_extract_validates_file_size(): void
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'credits' => 5]);

        Storage::fake('local');

        $response = $this->actingAs($user)->post('/extract', [
            // Create a file larger than 10MB (10241 KB)
            'receipt' => UploadedFile::fake()->create('big.jpg', 10241, 'image/jpeg'),
        ]);

        $response->assertSessionHasErrors('receipt');
        $this->assertEquals(5, $user->fresh()->credits);
    }

    public function test_credit_is_refunded_on_extraction_failure(): void
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'credits' => 3]);

        Storage::fake('local');

        // Bind a fake service that throws
        $this->app->bind(\App\Services\MistralOcrService::class, function () {
            return new class {
                public function extract($file, $userId) {
                    throw new \RuntimeException('Mistral API down');
                }
            };
        });

        $response = $this->actingAs($user)->post('/extract', [
            'receipt' => UploadedFile::fake()->image('receipt.jpg'),
        ]);

        $response->assertSessionHasErrors('extract');
        // Credit should be refunded
        $this->assertEquals(3, $user->fresh()->credits);
        // Should have a refund transaction
        $this->assertDatabaseHas('credit_transactions', [
            'user_id' => $user->id,
            'type'    => 'refund',
        ]);
    }
}
