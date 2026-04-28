<?php

use App\User;
use App\Outlet;
use Faker\Generator as Faker;

$factory->define(Outlet::class, function (Faker $faker) {
    $mapCenterLatitude = config('leaflet.map_center_latitude');
    $mapCenterLongitude = config('leaflet.map_center_longitude');
    $minLatitude = $mapCenterLatitude - 0.05;
    $maxLatitude = $mapCenterLatitude + 0.05;
    $minLongitude = $mapCenterLongitude - 0.07;
    $maxLongitude = $mapCenterLongitude + 0.07;

    return [
        'name'             => ucwords($faker->words(2, true)),
        'id_pemda'         => $faker->randomNumber(5),
        'kode_barang'      => $faker->isbn10,
        'register'         => $faker->randomNumber(4),
        'luas'             => $faker->randomFloat(2, 10, 1000),
        'tahun_pengadaan'  => $faker->year,
        'penggunaan'       => $faker->word,
        'harga'            => $faker->randomFloat(2, 1000000, 100000000),       
        'address'          => $faker->address,
        'keterangan'       => $faker->sentence,
        'nomor_sertifikat' => $faker->bothify('#####/####'),
        'tanggal_sertifikat' => $faker->date(),
        'hak'              => $faker->word,
        'latitude'         => $faker->latitude($minLatitude, $maxLatitude),
        'longitude'        => $faker->longitude($minLongitude, $maxLongitude),
        'polygon'          => null,
        'id_opd'           => $faker->randomNumber(2),
        'creator_id'       => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
