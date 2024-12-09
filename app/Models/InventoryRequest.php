<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryRequest extends Model
{
    protected $fillable = [
        'item_id',
        'department_id',
        'user_id',
        'quantity',
        'status',
        'remarks'
    ];

    // Get the item being requested
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    // Get the department making the request
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    // Get the user who made the request
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}