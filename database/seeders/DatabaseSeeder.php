<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Super Admin
        User::create([
            'nama' => 'NUR BAETI',
            'email' => 'Bee@superadmin.com',
            'role' => '1',
            'status' => 1,
            'password' => bcrypt('Baeti123!'),
        ]);

        // Admin
        User::create([
            'nama' => 'Putri Hadiyani',
            'email' => 'Putri@admin.com',
            'role' => '0',
            'status' => 1,
            'password' => bcrypt('Putri123'),
        ]);

        // Ajudan
        User::create([
            'nama' => 'Ajudan',
            'email' => 'ajudan@admin.com',
            'role' => '2', // 2 = Ajudan
            'status' => 1,
            'password' => bcrypt('Ajudan123!'),
        ]);
    }
}