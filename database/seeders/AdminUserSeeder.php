<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@notafy.dev'],
            [
                'name'              => 'Admin',
                'password'          => Hash::make('admin1234'),
                'plan'              => 'pro',
                'credits'           => 999999,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user ready: admin@notafy.dev / admin1234 (999,999 credits)');
    }
}
