<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Permissions\HasPermissionsTrait;

class UserRole extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $table = 'users_roles';

    protected $fillable = [
        'id',
        'user_id',
        'role_id'
    ];
}
