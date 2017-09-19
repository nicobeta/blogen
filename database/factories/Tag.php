
<?php

use Faker\Generator as Faker;

$factory->define(App\Tag::class, function (Faker $faker) {
    return [
        'tags' => implode(', ', $faker->words(3, false))
    ];
});
