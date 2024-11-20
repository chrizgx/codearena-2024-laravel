<?php

namespace Database\Seeders;

use App\Models\Post;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default user for login
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'info@netstudio.gr',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        User::factory(10)->create()->each(function (User $user) {
            $user->posts()->saveMany(Post::factory(rand(5, 10))->make());
        });
    }
}
