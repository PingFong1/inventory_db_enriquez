<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'status',
        'staff_count'
    ];

    // Get all items in this department
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'department', 'name');
    }

    // Get all requests for this department
    public function requests(): HasMany
    {
        return $this->hasMany(InventoryRequest::class);
    }

    // Get all users in this department
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Get all activities related to this department
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        $class = $this->status === 'active' ? 'success' : 'danger';
        return "<span class='badge bg-{$class}'>{$this->status}</span>";
    }
}