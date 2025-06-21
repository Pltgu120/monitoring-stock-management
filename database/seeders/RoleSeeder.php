<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah role sudah ada sebelum menambahkannya untuk menghindari duplikasi
        $roles = [
            ['name' => 'super_admin'],
            ['name' => 'admin'],
            ['name' => 'staff']
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }
    }
}
