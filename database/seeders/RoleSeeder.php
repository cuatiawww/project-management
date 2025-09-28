<?php
// database/seeders/RoleSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Bisa buat akun user baru, atur role, lihat semua project'
            ],
            [
                'name' => 'project_manager',
                'display_name' => 'Project Manager',
                'description' => 'Bisa buat project baru, invite member, assign task, monitor progress'
            ],
            [
                'name' => 'member',
                'display_name' => 'Member',
                'description' => 'Hanya bisa lihat task yang ditugaskan, update status task'
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}