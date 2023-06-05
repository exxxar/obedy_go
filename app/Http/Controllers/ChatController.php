<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function getChats()
    {
        return Inertia::render('Chats');
    }
}
