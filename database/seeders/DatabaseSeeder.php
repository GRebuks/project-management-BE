<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
         'email' => 'test@example.com',
        ]);

        Workspace::factory(3)->create();
        // Populate the user workspace pivot table

        $workspaces = Workspace::all();

        User::all()->each(function ($user) use ($workspaces) {
            $user->workspaces()->attach(
                $workspaces->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
