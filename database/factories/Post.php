<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    $user = factory(App\User::class)->create();

    return [
        'title' => $faker->sentence,
        'body' => $faker->text,
        'user_id' => $user->id,
    ];
});
