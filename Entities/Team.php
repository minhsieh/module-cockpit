<?php

namespace Modules\Cockpit\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Cockpit\Services\Teamwork\Traits\TeamworkTeamTrait;
use Modules\Cockpit\Events\TeamDeleted;

class Team extends Model
{
    use TeamworkTeamTrait;

    protected $fillable = [
        'tid',
        'name',
        'is_active',
        'contact',
        'tel',
        'email',
        'remark'
    ];

    public static $attrs = [
        'tid' => '團隊編號',
        'name' => '團隊名稱',
        'is_active' => '是否啟用',
        'contact' => '聯絡人',
        'tel' => '電話',
        'email' => 'Email信箱',
        'remark' => '備註',
    ];

    public static $rules = [
        'tid' => 'required|string|alpha_num|max:20|unique:teams',
        'name' => 'required|string|max:40',
        'contact' => 'required|string|max:30',
        'tel' => 'required|string|max:30',
        'email' => 'required|string|email|max:255',
    ];

    protected $dispatchesEvents = [
        'deleted' => TeamDeleted::class,
    ];

    public function roles()
    {
        return $this->belongsToMany('Modules\Cockpit\Permission\Models\Role', 'team_roles', 'team_id', 'role_id');
    }
}
