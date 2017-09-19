<?php

use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    $user = factory(App\User::class)->create();
    $post = factory(App\Post::class)->create();

    return [
        'body' => $faker->text,
        'user_id' => $user->id,
        'post_id' => $post->id
    ];
});
