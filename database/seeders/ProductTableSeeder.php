<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::truncate();
        $products = json_decode(Storage::disk('public')->get("data.json"));
        foreach ($products->data as $product)

            Product::create([
                'title' => $product->title,
                'description' => $product->description ?? null,
                'day_index' => $product->day_index ?? null,
                'image' => $product->image ?? null,
                'price' => $product->price ?? 0,
                'weight' => $product->weight ?? 0,
                'category_id' => $product->category_id ?? null,
                'food_part_id' => $product->food_part_id ?? null,
                'positions' => $product->positions ?? [],
                'is_week' => $product->is_week ?? false,
                'addition' => $product->addition ?? false,
                'checked' => $product->checked ?? null,
                'disabled' => $product->disabled ?? null
            ]);
    }
}
