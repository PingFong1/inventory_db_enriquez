<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'min_quantity',
        'max_quantity',
        'department_id',
        'category',
        'status'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}