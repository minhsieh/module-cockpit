<?php

namespace Modules\Cockpit\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Cockpit\Permission\Traits\HasRoles as HasRoles;
use Modules\Cockpit\Services\Teamwork\Traits\UserHasTeams as UserHasTeams;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use UserHasTeams;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'team_id',
        'last_login'
    ];

    public static $attrs = [
        'name' => '姓名',
        'email' => 'Email電子信箱',
        'password' => '密碼',
    ];

    public static $rules = [
        'name' => 'required|string|max:40',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ];
}
