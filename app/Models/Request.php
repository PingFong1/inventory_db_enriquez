<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Request extends Model
{
    protected $fillable = [
        'item_id',
        'dept_user_id',
        'quantity_requested',
        'requested_date',
        'delivery_date',
        'approved_date',
        'status'
    ];

    protected $casts = [
        'requested_date' => 'date',
        'delivery_date' => 'date',
        'approved_date' => 'date'
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function deptUser(): BelongsTo
    {
        return $this->belongsTo(DeptUser::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'dept_request');
    }
} 