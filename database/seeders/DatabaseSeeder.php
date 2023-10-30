<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

         \App\Models\User::factory()->create([
             'name' => 'Test User',
             'email' => 'test@example.com',
         ]);
        \App\Models\User::factory()->create([
            'name' => 'Second Test User',
            'email' => 'test2@example.com',
        ]);

        // Public projects to Test User 1
        \App\Models\Project::factory()->create([]);
        \App\Models\Project::factory()->create([]);

         // Public project to Test User 2
        \App\Models\Project::factory()->create([
            'user_id' => 2,
        ]);

        // Private project to Test User 2
        \App\Models\Project::factory()->create([
            'user_id' => 2,
            'is_public' => false,
        ]);
    }
}
