<?php

use App\User;
use App\Outlet;
use App\Opd;
use Faker\Generator as Faker;

$factory->define(Opd::class, function (Faker $faker) {
    return [
        'nama_opd'   => ucwords($faker->words(2, true)),
        'sub_opd'    => $faker->sub_opd,
        'upt'        => $faker->upt,
        'creator_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
