<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function chat()
    {
        return view('chat');
    }
    public function groupChat()
    {
        $roomId = 12345678;
        return view('group-chat', compact('roomId'));
    }
}
