<?php

use Faker\Generator as Faker;
use \Modules\Cockpit\Entities\User;
use \Modules\Cockpit\Entities\Team;

$factory->define(User::class, function (Faker $faker) {

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('123qwe123'),
        'is_active' => 1,
        'last_login' => date('Y-m-d H:i:s')
    ];
});