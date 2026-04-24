<?php

namespace App\Http\Controllers;

use App\Outlet;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $outlets = \DB::table('opd')
                    ->leftJoin('outlets', 'outlets.id_opd', '=', 'opd.id_opd')
                    ->select('opd.id_opd', 'opd.nama_opd', 'opd.sub_opd', 'opd.upt', 'outlets.*')
                    ->whereColumn('opd.id_opd', 'outlets.id_opd')
                    ->orderBy('opd.id_opd')
                    ->get()
                    ->map(function ($outlet) {
                        // Menambahkan atribut warna berdasarkan nama outlets
                        if (strpos($outlet->name, 'Tanah Usaha') !== false) {
                            $outlet->color = 'red';
                        } elseif (strpos($outlet->name, 'Tanah Bangunan') !== false) {
                            $outlet->color = 'blue';
                        } elseif (strpos($outlet->name, 'Tanah Untuk Jalan') !== false) {
                            $outlet->color = 'green';
                        } elseif (strpos($outlet->name, 'Tanah Untuk Makam') !== false) {
                            $outlet->color = 'green';
                        } elseif (strpos($outlet->name, 'Tanah Kosong') !== false) {
                            $outlet->color = 'black';
                        } elseif (strpos($outlet->name, 'Tanah Lapangan') !== false) {
                            $outlet->color = 'orange';
                        } else {
                            $outlet->color = 'yellow'; // Warna default
                        }
                        return $outlet;
                    });;

        $totalData = \DB::table('outlets')->count('id');
        
        $totalOPD = \DB::table('opd')
                    ->leftJoin('outlets', 'outlets.id_opd', '=', 'opd.id_opd')
                    ->where('outlets.name', 'Tanah Usaha')
                    ->distinct('opd.id_opd')
                    ->count('opd.id_opd');

        $totalLuas = \DB::table('opd')
                    ->leftJoin('outlets', 'outlets.id_opd', '=', 'opd.id_opd')
                    ->where('outlets.name', 'Tanah Usaha')
                    ->sum('outlets.luas');

        $totalbpka = \DB::table('opd')
                    ->leftJoin('outlets', 'outlets.id_opd', '=', 'opd.id_opd')
                    ->where('outlets.name', 'Tanah Usaha')
                    ->where('opd.nama_opd', 'BADAN PENGELOLAAN KEUANGAN DAN ASET')
                    ->select(\DB::raw('COUNT(opd.id_opd) AS jumlah_opd'))
                    ->first();

        $totalbpkaluas = \DB::table('opd')
                    ->leftJoin('outlets', 'outlets.id_opd', '=', 'opd.id_opd')
                    ->where('outlets.name', 'Tanah Usaha')
                    ->where('opd.nama_opd', 'BADAN PENGELOLAAN KEUANGAN DAN ASET')
                    ->groupBy('opd.nama_opd')
                    ->sum('outlets.luas');

        $grafiktotal = \DB::table('outlets')
                ->join('opd', 'opd.id_opd', '=', 'outlets.id_opd')
                ->select('outlets.name', \DB::raw('COUNT(opd.nama_opd) as jumlah'), 'opd.sub_opd', 'opd.upt', 'opd.nama_opd')
                ->where('outlets.name', 'Tanah Usaha')
                ->groupBy('opd.nama_opd', 'outlets.name', 'opd.sub_opd', 'opd.upt')
                ->orderBy('opd.nama_opd')
                ->get();

        return view('home', [
            'outlets' => $outlets,
            'totalData' => $totalData,
            'totalOPD' => $totalOPD,
            'totalLuas' => $totalLuas,
            'totalbpka' => $totalbpka,
            'totalbpkaluas' => $totalbpkaluas,
            'grafiktotal' => $grafiktotal,
        ]);
    }
}
