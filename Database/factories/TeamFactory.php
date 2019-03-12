<?php

use Faker\Generator as Faker;
use \Modules\Cockpit\Entities\Team;

$factory->define(Team::class, function (Faker $faker) {
    return [
        'tid' => $faker->word,
        'name' => $faker->firstName,
        'is_active' => 1,
        'contact' => $faker->name,
        'tel' => $faker->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'remark' => $faker->text
    ];
});