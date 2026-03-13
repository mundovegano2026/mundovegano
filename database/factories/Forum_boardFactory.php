<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Forum_board::class, function (Faker $faker) {
    return [
        'name' => $faker->text(50)
    ];
});
