<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'admin_id',
        'description',
        'action_type',
        'item_id',
        'department_id'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Helper method to get appropriate icon
    public function getIconAttribute()
    {
        return match($this->action_type) {
            'create' => 'plus-circle',
            'update' => 'edit',
            'delete' => 'trash',
            'stock_in' => 'arrow-up',
            'stock_out' => 'arrow-down',
            default => 'circle'
        };
    }
} 