<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $roles = [
            'admin',
            'moderator',
            'moderator',
            'user',
            'user',
            'user',
            'user',
            'user',
            'user',
            'user',
        ];

        foreach ($roles as $i => $role) {
            User::create([
                'name' => ucfirst($role) . ' User ' . ($i + 1),
                'email' => "user" . ($i + 1) . "@gmail.com",
                'role' => $role,
                'avatar' => null,
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
