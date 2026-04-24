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
        'id_pemda'         => $faker->id_pemda,
        'kode_barang'      => $faker->kode_barang,
        'register'         => $faker->register,
        'luas'             => $faker->luas,
        'tahun_pengadaan'  => $faker->tahun_pengadaan,
        'penggunaan'       => $faker->penggunaan,
        'harga'            => $faker->harga,       
        'address'          => $faker->address,
        'keterangan'       => $faker->keterangan,
        'nomor_sertifikat' => $faker->nomor_sertifikat,
        'tanggal_sertifikat' => $faker->tanggal_sertifikat,
        'hak'              => $faker->hak,
        'latitude'         => $faker->latitude($minLatitude, $maxLatitude),
        'longitude'        => $faker->longitude($minLongitude, $maxLongitude),
        'polygon'          => $faker->polygon,
        'id_opd'           => $faker->id_opd,
        'creator_id'       => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
