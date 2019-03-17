<?php

namespace Modules\Cockpit\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Cockpit\Permission\Traits\HasRoles as HasRoles;
use Modules\Cockpit\Services\Teamwork\Traits\UserHasTeams as UserHasTeams;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;

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

    public function sendPasswordResetNotification($token)
    {
        return (new MailMessage)
            ->subject(Lang::getFromJson('Reset Password Notification'))
            ->line(Lang::getFromJson('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::getFromJson('Reset Password'), url(config('app.url').route('cockpit.password.reset', ['token' => $token], false)))
            ->line(Lang::getFromJson('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.users.expire')]))
            ->line(Lang::getFromJson('If you did not request a password reset, no further action is required.'));
    }
}
