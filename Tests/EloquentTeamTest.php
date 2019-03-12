<?php

namespace Modules\Cockpit\Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Cockpit\Entities\Team;

class EloquentTeamTest extends TestCase
{
    //use RefreshDatabase;
    use \Illuminate\Foundation\Testing\DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testTeamModelCreate()
    {
        $teams = factory(Team::class,3)->create();

        foreach($teams as $team){
            $this->assertDatabaseHas('teams', ['tid' => $team->tid]);
        }
    }
}
