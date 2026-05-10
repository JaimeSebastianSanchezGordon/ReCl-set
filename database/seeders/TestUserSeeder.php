<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Crea un usuario de prueba para desarrollo.
     * Este usuario es el que se usa como mock en GarmentController::currentUserId().
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@recloset.com'],
            [
                'name'     => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        $user = User::where('email', 'test@recloset.com')->first();
        $this->command->info("Test user ready — ID: {$user->id}, Email: {$user->email}");
    }
}
