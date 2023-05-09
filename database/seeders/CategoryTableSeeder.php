<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Супы',
            ],
            [
                'title' => 'Напитки',
            ],
            [
                'title' => 'Десерты',
            ],
            [
                'title' => 'Соусы',
            ],
        ];
        Category::truncate();
        foreach ($categories as $category){
            Category::create([
               'title'=>$category['title']
            ]);
        }
    }
}
