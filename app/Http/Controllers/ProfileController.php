<?php

namespace App\Http\Controllers;

use App\Actions\ImageStoreAction;
use App\Http\Requests\Auth\ProfileUpdateRequest;
use App\Http\Requests\ImageRequest;
use App\Http\Resources\MenuResource;
use App\Http\Resources\ProfileResource;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function getSpecialists()
    {
        $specialists = User::role('specialist')->get();

        return Inertia::render('Specialists',
            [
                'specialists' => ProfileResource::collection($specialists)
            ]
        );
    }

    public function getProfile()
    {
        $user = User::find(auth()->id());
        $menus = [];

        if($user->hasRole('specialist'))
            $menus =  MenuResource::collection(Menu::where('user_id', auth()->id())->get());

        return response()->json([
            'profile'=>new ProfileResource($user),
            'menus'=>$menus
        ]);
    }

    public function updateProfile(ProfileUpdateRequest $request)
    {
        $user = User::find(auth()->id());
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->addresses = $request->addresses;
        $user->description = $request->description;
        if ($request->has('password') && $request->password !== '') {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return response(null, 200);
    }

    public function updateAvatar(ImageRequest $request, ImageStoreAction $imageStoreAction)
    {
        $user = User::find(auth()->id());
        $user->image = $imageStoreAction($request->file('image'),  'users',"avatar-$user->id");
        $user->save();
        return response()->json(['avatar'=>$user->image]);
    }
}
