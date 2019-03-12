<?php

namespace Modules\Cockpit\Entities;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'tid',
        'name',
        'is_active',
        'contact',
        'tel',
        'email',
        'remark'
    ];
}
