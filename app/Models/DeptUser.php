<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeptUser extends Authenticatable
{
    use HasFactory;

    protected $table = 'dept_users';

    protected $fillable = [
        'dept_name',
        'user_name',
        'email',
        'password',
        'phone'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function requests()
    {
        return $this->hasMany(Request::class, 'dept_user_id');
    }
}