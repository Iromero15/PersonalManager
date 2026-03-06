<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usamos el ORM de Laravel (Eloquent) para crear el registro
        User::create([
            'name' => 'Nacho',
            'email' => 'admin@chillcraft.lat',
            // Hash::make usa bcrypt por defecto para encriptar
            'password' => Hash::make(env('ADMIN_PASSWORD', 'clave_por_defecto')),
        ]);
    }
}