<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'item_id',
        'type',
        'quantity',
        'remarks',
        'performed_by'
    ];

    // Get the item associated with this movement
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    // Get the admin who performed this movement
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'performed_by');
    }
} 