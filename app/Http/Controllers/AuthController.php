<?php

namespace App\Http\Controllers;

use App\Events\SendSmsEvent;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();

        if(!$user) {
            $user = User::create([
                'phone' => $request->phone,
                'password' => Hash::make($request->phone),
            ]);

            event(new SendSmsEvent($request->phone, "Рады Вас видеть в ОбедыGO! Ваши логин и пароль -".$request->phone));
        }

        $user->name = $request->name;
        $user->addresses = $request->addresses;
        $user->save();

        Auth::login($user);

        return response()->json(['status'=>'ok']);
    }

    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->json(new UserResource(Auth::user()));

    }

    public function getUser()
    {
        return response()->json(new UserResource(Auth::user()));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            "token"=>csrf_token()],
            200);
    }
}
