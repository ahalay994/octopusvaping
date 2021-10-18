<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'slug',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function roles() {

        return $this->belongsToMany(Role::class,'roles_permissions');

    }

    public function users() {

        return $this->belongsToMany(User::class,'users_permissions');

    }
}
