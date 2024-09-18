<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecordingController extends Controller
{

    public function uploadVideo(Request $request)
    {
        $request->validate([
            'video' => 'required|mimes:webm|max:100000',
        ]);

        $userId = auth()->id();
        $today = now()->format('Y-m-d');
        $filename = "{$today}-recording.webm";

        $path = $request->file('video')->storeAs("public/recordings/{$userId}", $filename);

        return response()->json(['path' => $path]);
    }
}
