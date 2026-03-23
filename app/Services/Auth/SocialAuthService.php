<?php

namespace App\Services\Auth;

use App\Models\CreditTransaction;
use App\Models\User;
use App\Models\UserIdentity;
use Illuminate\Support\Str;
use RuntimeException;

class SocialAuthService
{
    public function findOrCreateFromProvider(
        string $provider,
        string $providerUserId,
        ?string $email,
        string $name,
        ?string $avatar,
        array $raw = []
    ): User {
        $identity = UserIdentity::where('provider', $provider)
            ->where('provider_user_id', $providerUserId)
            ->first();

        if ($identity) {
            $identity->update([
                'email' => $email,
                'avatar' => $avatar,
                'data' => $raw,
            ]);

            return $identity->user;
        }

        $user = null;
        if ($email) {
            $user = User::where('email', $email)->first();
        }

        if (!$user) {
            if (!$email) {
                throw new RuntimeException('No email returned from provider.');
            }

            $user = User::create([
                'name'                    => $name,
                'email'                   => $email,
                'password'                => Str::random(32),
                'credits'                 => 10,
                'credits_last_refilled_at' => now(),
            ]);
            CreditTransaction::record($user->id, 'bonus', 10, 'Welcome bonus');
        }

        UserIdentity::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_user_id' => $providerUserId,
            'email' => $email,
            'avatar' => $avatar,
            'data' => $raw,
        ]);

        return $user;
    }
}
