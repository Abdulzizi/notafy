<?php

namespace Tests\Feature;

use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_unverified_user_cannot_access_extract(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $response = $this->actingAs($user)->get('/extract');

        // Should redirect to email verification notice
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_verified_user_can_access_extract(): void
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'credits' => 5]);

        $response = $this->actingAs($user)->get('/extract');

        $response->assertStatus(200);
    }

    public function test_unverified_user_cannot_access_history(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $response = $this->actingAs($user)->get('/history');

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_unverified_user_cannot_access_account(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $response = $this->actingAs($user)->get('/account');

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_guest_is_redirected_to_login_from_extract(): void
    {
        $response = $this->get('/extract');

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email'    => 'login@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'login@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout');

        $this->assertGuest();
    }

    public function test_new_user_gets_only_welcome_bonus_not_weekly_refill(): void
    {
        $this->post('/register', [
            'name'                  => 'New User',
            'email'                 => 'newuser@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'newuser@example.com')->first();
        $user->refillCreditsIfDue();

        $this->assertEquals(10, $user->fresh()->credits);
        $this->assertDatabaseCount('credit_transactions', 1);
        $this->assertDatabaseHas('credit_transactions', ['type' => 'bonus', 'credits' => 10]);
    }
}
