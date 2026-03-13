<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Store::class, function (Faker $faker) {
    return [
        'name' => $faker->text(50),
        'chain_id' => $faker->randomDigitNot(0),
        'freguesia_id' => $faker->randomDigitNot(0)
    ];
});
