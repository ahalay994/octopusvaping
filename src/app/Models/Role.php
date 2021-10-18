<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
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

    public function permissions() {

        return $this->belongsToMany(Permission::class,'roles_permissions');

    }

    public function users() {

        return $this->belongsToMany(User::class,'users_roles');

    }
}
