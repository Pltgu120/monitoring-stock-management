<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil role berdasarkan nama
        $role_super_admin = Role::where('name', 'super_admin')->first();
        $role_admin = Role::where('name', 'admin')->first();
        $role_staff = Role::where('name', 'staff')->first();

        // Pastikan setiap role ditemukan sebelum membuat user
        if ($role_staff) {
            User::create([
                "name" => "ryugen",
                "username" => "ryugen",
                "role_id" => $role_staff->id,
                "password" => bcrypt('12345678')
            ]);
        } else {
            $this->command->error('Role "staff" not found!');
        }

        if ($role_admin) {
            User::create([
                "name" => "admin",
                "username" => "admin",
                "role_id" => $role_admin->id,
                "password" => bcrypt('12345678')
            ]);
        } else {
            $this->command->error('Role "admin" not found!');
        }

        if ($role_super_admin) {
            User::create([
                "name" => "super admin",
                "username" => "super_admin",
                "role_id" => $role_super_admin->id,
                "password" => bcrypt('12345678')
            ]);
        } else {
            $this->command->error('Role "super_admin" not found!');
        }
    }
}
