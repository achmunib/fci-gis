<?php

namespace App\Http\Controllers\Api;

use App\Outlet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics.
     * GET /api/dashboard/stats
     */
    public function stats()
    {
        // 1. Total Data Tanah
        $totalAset = DB::table('outlets')->count();

        // 2. Total Luas (m2)
        $totalLuas = DB::table('outlets')->sum('luas');

        // 3. Total OPD yang memiliki Tanah Usaha
        $totalOpdTanahUsaha = DB::table('outlets')
            ->where('name', 'LIKE', '%Tanah Usaha%')
            ->distinct('id_opd')
            ->count('id_opd');

        // 4. Drill-down BPKA (This is just an example statistic based on the old dashboard)
        $drillDownBPKA = DB::table('outlets')
            ->join('opd', 'opd.id_opd', '=', 'outlets.id_opd')
            ->where('opd.nama_opd', 'LIKE', '%BPKA%')
            ->count();

        // 5. Data for Bar Chart: Jumlah Tanah Usaha per OPD
        $chartData = DB::table('opd')
            ->join('outlets', 'outlets.id_opd', '=', 'opd.id_opd')
            ->where('outlets.name', 'LIKE', '%Tanah Usaha%')
            ->select('opd.nama_opd', DB::raw('count(outlets.id) as total'))
            ->groupBy('opd.nama_opd')
            ->orderByDesc('total')
            ->get();

        return response()->json([
            'total_aset' => $totalAset,
            'total_luas' => $totalLuas,
            'total_opd_tanah_usaha' => $totalOpdTanahUsaha,
            'drilldown_bpka' => $drillDownBPKA,
            'chart_data' => $chartData
        ]);
    }
}
