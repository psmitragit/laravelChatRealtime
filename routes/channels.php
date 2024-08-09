<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
