<?php

namespace App\Http\Controllers;

use App\Enums\FoodPartEnum;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function getProducts($foodPart){

        $partId = FoodPartEnum::getValueByKey($foodPart);

        $products = Product::where('food_part_id', $partId)->get();
        $categories = Category::with('products')->has('products')->get();

        return Inertia::render('Products', [
            'products'=> ProductResource::collection($products),
            'categories'=> CategoryResource::collection($categories)
        ]);
    }
}
