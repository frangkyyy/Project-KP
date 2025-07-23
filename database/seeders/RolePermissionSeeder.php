<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Buat role admin jika belum ada
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);

        // Buat permission edit-kelas jika belum ada
        $permissionEdit = Permission::firstOrCreate(['name' => 'edit-kelas']);

        // Berikan permission ke admin
        $roleAdmin->givePermissionTo($permissionEdit);
    }
}
