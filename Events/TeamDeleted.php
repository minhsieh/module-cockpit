<?php

namespace Modules\Cockpit\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Cockpit\Entities\Team;
use Illuminate\Support\Facades\Log;

class TeamDeleted
{
    use SerializesModels;

    protected $team;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Team $team)
    {
        Log::debug($team->name." is deleted");
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
