<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->text(50),
        'parent_id' => $faker->randomDigitNot(0),
        'path' => $faker->text(50),
        'level' => $faker->randomDigitNot(0)
    ];
});
