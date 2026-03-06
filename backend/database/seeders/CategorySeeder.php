<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Creamos categorías asignadas a tu usuario administrador (ID 1)
        $categories = ['Supermercado', 'Sueldo', 'Auto', 'Salidas', 'Servicios'];
        
        foreach ($categories as $category) {
            Category::create([
                'user_id' => 1,
                'name' => $category
            ]);
        }
    }
}