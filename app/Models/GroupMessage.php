<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    use HasFactory;
    protected $table = 'group_messages';

    protected $fillable = [
        'user_id',
        'room_id',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
