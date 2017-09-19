<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    $user = factory(App\User::class)->create();
    $tags = factory(App\Tag::class)->make()->toArray();

    return [
        'title' => $faker->sentence,
        'body' => $faker->text,
        'user_id' => $user->id,
        'tags' => $tags['tags']
    ];
});
