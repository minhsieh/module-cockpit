<?php

namespace Modules\Cockpit\Entities;

use Illuminate\Database\Eloquent\Model;

class TeamRole extends Model
{
    protected $fillable = ['team_id','role_id'];
}
