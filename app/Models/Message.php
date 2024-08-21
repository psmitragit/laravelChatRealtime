<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ['from', 'to', 'message', 'is_seen', 'file'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'from');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'to');
    }
}
