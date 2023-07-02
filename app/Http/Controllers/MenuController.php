<?php

namespace App\Http\Controllers;

use App\Actions\ImageStoreAction;
use App\Http\Requests\Menu\StoreUpdateMenuRequest;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Models\Product;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MenuController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Menu');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateMenuRequest $request, ImageStoreAction $imageStoreAction)
    {
        $menu = Menu::updateOrCreate(
            [
                'id' => $request->id
            ],
            ['user_id' => auth()->id(),
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'slug' => Str::slug($request->title)
            ]);
        if ($request->hasFile('image')) {
            $menu->image = $imageStoreAction($request->file('image'), 'menus', "menu-$menu->id");
            $menu->save();
        }
        $weekPositions = [];
        $weekPrice = 0;
        $weekWeight = 0;
        foreach ($request->products as $index => $productItem) {
            $product = Product::updateOrCreate(
                [
                    'id' => array_key_exists('id', $productItem) ? $productItem['id'] : null
                ],
                [
                    'title' => $productItem['title'],
                    'description' => $productItem['description'],
                    'positions' => array_key_exists('positions', $productItem) ? $productItem['positions'] : [],
                    'price' => $productItem['price'],
                    'weight' => $productItem['weight'],
                    'day_index' => $index + 1
                ]);
            if ($request->hasFile("products.$index.image")) {
                $product->image = $imageStoreAction($request->file("products.$index.image"), 'products', "product-$product->id");
                $product->save();
            }
            $weekPositions[] = [
                'image' => is_null($product->image) ? config('app.logo') : $product->image,
                'title' => $product->title,
                'weight' => $product->weight
            ];
            $weekPrice+=$product->price;
            $weekWeight+=$product->weight;
            $menu->products()->attach($product);
        }
        $weekProduct = Product::create([
            'title' => $menu->title . ' неделя',
            'image' => config('app.logo'),
            'day_index'=>7,
            'price'=>$weekPrice,
            'weight'=>$weekWeight,
            'positions'=>$weekPositions,
            'is_week'=>true
        ]);
        $menu->products()->attach($weekProduct);
        return response(null, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $menu = Menu::where(['id' => $id, 'user_id' => auth()->id()])->first();
        if (!$menu)
            abort(404);
        return Inertia::render('Menu', [
            'menu' => new MenuResource($menu)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $menu = Menu::where(['id' => $id, 'user_id' => auth()->id()])->first();
        if (!$menu)
            abort(404);
        $menu->delete();
        return response(null, 200);
    }


}
