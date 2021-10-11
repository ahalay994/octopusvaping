<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Permissions\HasPermissionsTrait;

class UserPermission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $table = 'users_permissions';

    protected $fillable = [
        'user_id',
        'permission_id'
    ];
}
