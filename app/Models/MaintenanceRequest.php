<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_name',
        'contact_info',
        'property_location',
        'description',
        'priority',
        'status',
    ];
}
