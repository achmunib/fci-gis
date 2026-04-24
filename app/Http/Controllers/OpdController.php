<?php
namespace App\Http\Controllers;

use App\Opd;
use Illuminate\Http\Request;

class OpdController extends Controller
{
    /**
     * Display a listing of the outlet.
     *
     * @return \Illuminate\View\View
     */
     public function index()
    {
        $this->authorize('manage_outlet');

        $opdQuery = Opd::query();
        $opdQuery->where('nama_opd', 'like', '%'.request('q').'%');
        $opd = $opdQuery->paginate(25);

        return view('opd.opd_data', compact('opd'));
    }

     /**
     * Display the specified OPD.
     *
     * @param  \App\Opd  $opd
     * @return \Illuminate\View\View
     */

    public function opd_data(Opd $opd)
    {
        $opd= Opd::with('opd')->where('id_opd', '=', $opd->id_opd)->first();
        return view('opd.opd_data', compact('opd'));

    }

    public function store(Request $request)
    {
        $this->authorize('manage_outlet');

        $newOpd = $request->validate([
            'nama_opd'  => 'required|max:255',
            'sub_opd'   => 'nullable|max:255',
            'upt'       => 'nullable|max:255',
        ]);
        $newOpd['creator_id'] = auth()->id();
        
        $opd = Opd::create($newOpd);


        return redirect()->route('opd_create', $opd);
    }

    public function opd_create(Opd $opd)
    {
        return view ('opd.opd_create', compact('opd'));
        
    }
    
    /*public function opd_data(Opd $opd)
    {
        $opd =  Opd::with('opd')->where('id_opd', '=', $opd->id_opd)->first();

        return view('opd.opd_data', compact('opd'));
        
    }*/


    /*public function opd_create()
    {
        $opd_c = \DB::table('data_opd')->select('id_opd','nama_opd','sub_opd','upt')->get();

        return view('opd.opd_create')->with('data_opd', $opd_c);
    }*/

    
}
