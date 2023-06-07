<?php

namespace App\Http\Controllers;

use App\Http\Resources\SpecialistResource;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function getSpecialists()
    {
        $specialists = User::role('specialist')->get();

        return Inertia::render('Specialists',
            [
                'specialists'=>SpecialistResource::collection($specialists)
            ]
        );
    }
}
