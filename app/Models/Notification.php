<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'message',
        'read'
    ];

    protected $casts = [
        'read' => 'boolean'
    ];
}