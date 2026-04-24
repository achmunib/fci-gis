<?php

namespace App\Http\Controllers;

use App\Outlet;
use App\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the outlet.
     *
     * @return \Illuminate\View\View
     */
    public function index(Outlet $opdQuery)
    {
        $this->authorize('manage_outlet');

        $opdQuery = Opd::query();
        $opdQuery->where('nama_opd', 'like', '%'.request('q').'%');

        return view('outlets.index', compact('opdQuery'));
    }

    
    
}
