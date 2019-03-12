<?php

namespace Modules\Cockpit\Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Cockpit\Entities\User;
use Modules\Cockpit\Entities\Team;

class EloquentUserTest extends TestCase
{
    //use RefreshDatabase;
    use \Illuminate\Foundation\Testing\DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_user_model_is_creatable()
    {
        $users = factory(User::class,3)->create();

        foreach($users as $user){
            $this->assertDatabaseHas('users', ['email' => $user->email]);
        }
    }
}
