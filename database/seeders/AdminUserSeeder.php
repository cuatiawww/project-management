<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::where('name', 'admin')->first();
        
        // Hapus admin lama kalau ada
        User::where('email', 'admin@projectmanagement.com')->delete();
        
        // Buat admin baru
        User::create([
            'name' => 'Ptigo Admin',
            'email' => 'ptigo.karir@gmail.com',
            'password' => Hash::make('password123'),
            'role_id' => $adminRole->id,
            'email_verified_at' => now(),
        ]);
    }
}