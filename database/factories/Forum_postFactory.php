<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Forum_post::class, function (Faker $faker) {
    return [
        'title' => $faker->text(20),
        'body' => $faker->text(150),
        'forum_board_id' => $faker->randomDigitNot(0),
        'forum_post_parent_id' => $faker->randomDigitNot(0)
    ];
});
