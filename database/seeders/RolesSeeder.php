<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Owner',
            'description' => 'The owner has full control over the workspace.',
        ]);

        Role::create([
            'name' => 'Admin',
            'description' => 'Admins have administrative privileges but cannot delete the workspace.',
        ]);

        Role::create([
            'name' => 'Editor',
            'description' => 'Editors can read and change information, but cannot delete the workspace or manage users.',
        ]);

        Role::create([
            'name' => 'Guest',
            'description' => 'Read-only users have read-only access to the workspace, without the ability to make changes.',
        ]);
    }
}
