<?php

namespace App\Http\Controllers;

use App\Outlet;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class OutletController extends Controller
{
    /**
     * Display a listing of the outlet.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('manage_outlet');

        $outletQuery = Outlet::query();
        $outletQuery->leftJoin('opd', 'opd.id_opd', '=', 'outlets.id_opd');
        $outletQuery->where(function ($query) {
                    $query->where('outlets.name', 'like', '%'.request('q').'%')
                          ->orWhere('outlets.id_pemda', 'like', '%'.request('q').'%')
                          ->orWhere('outlets.address', 'like', '%'.request('q').'%')
                          ->orWhere('outlets.kode_barang', 'like', '%'.request('q').'%')
                          ->orWhere('outlets.luas', 'like', '%'.request('q').'%')
                          ->orWhere('outlets.nomor_sertifikat', 'like', '%'.request('q').'%')
                          ->orWhere('outlets.tanggal_sertifikat', 'like', '%'.request('q').'%')
                          ->orWhere('outlets.hak', 'like', '%'.request('q').'%')
                          ->orWhere('outlets.tahun_pengadaan', 'like', '%'.request('q').'%')
                          ->orWhere('outlets.polygon', 'like', '%'.request('q').'%'); // Tambahkan kolom lain jika perlu
                });
        $outletQuery->select('outlets.*', 'opd.*');
        $outlets = $outletQuery->paginate(25);
        

        return view('outlets.index', compact('outlets'));
    }

   /**
     * Show the form for creating a new outlet.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $outlets = \DB::table('opd')->get();

        $this->authorize('create', new Outlet);

        return view('outlets.create')->with('outlets', $outlets);
    }

    /**
     * Store a newly created outlet in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', new Outlet);

        $newOutlet = $request->validate([
            'id_pemda'          => 'nullable|max:50',
            'name'              => 'required|max:60',
            'kode_barang'       => 'nullable|max:20',
            'register'          => 'nullable|max:2',
            'luas'              => 'nullable|max:11',
            'tahun_pengadaan'   => 'nullable|max:4',
            'penggunaan'        => 'nullable|max:50',
            'harga'             => 'nullable|max:20',
            'address'           => 'nullable|max:255',
            'keterangan'        => 'nullable|max:255',
            'nomor_sertifikat'  => 'nullable|max:255',
            'tanggal_sertifikat'=> 'nullable|max:255',
            'hak'               => 'nullable|max:20',
            'file_path'         => 'nullable|mimes:pdf|max:10000',
            'file_photo'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
            'latitude'          => 'nullable|required_with:longitude|max:15',
            'longitude'         => 'nullable|required_with:latitude|max:15',
            'polygon'           => 'nullable|required_with:polygon',
            'id_opd'            => 'nullable|max:11',
        ]);

        // Mengupload dan menyimpan file PDF
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('pdfs', 'public');
            $newOutlet['file_path'] = $filePath;
        }

        // Mengupload dan menyimpan file foto
        if ($request->hasFile('file_photo')) {
            $filePhoto = $request->file('file_photo')->store('photos', 'public');
            $newOutlet['file_photo'] = $filePhoto;
        }
        
        $newOutlet['creator_id'] = auth()->id();
        
        $outlet = Outlet::create($newOutlet);
        
        return redirect()->route('outlets.show', $outlet);
               
    }

    /**
     * Display the specified outlet.
     *
     * @param  \App\Outlet  $outlet
     * @return \Illuminate\View\View
     */
    
    public function show(Outlet $outlet)
    {
        return view('outlets.show', compact('outlet'));
    }
    /**
     * Show the form for editing the specified outlet.
     *
     * @param  \App\Outlet  $outlet
     * @return \Illuminate\View\View
     */
    public function edit(Outlet $outlet)
    {
        $this->authorize('update', $outlet);

        return view('outlets.edit', compact('outlet'));
    }

    /**
     * Update the specified outlet in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Outlet  $outlet
     * @return \Illuminate\Routing\Redirector
     */
    public function update(Request $request, Outlet $outlet)
    {
        $this->authorize('update', $outlet);

        $outletData = $request->validate([
            'id_pemda'          => 'nullable|max:50',
            'name'              => 'required|max:60',
            'kode_barang'       => 'nullable|max:20',
            'register'          => 'nullable|max:2',
            'luas'              => 'nullable|max:11',
            'tahun_pengadaan'   => 'nullable|max:4',
            'penggunaan'        => 'nullable|max:50',
            'harga'             => 'nullable|max:20',
            'address'           => 'nullable|max:255',
            'keterangan'        => 'nullable|max:255',
            'nomor_sertifikat'  => 'nullable|max:255',
            'tanggal_sertifikat'=> 'nullable|max:255',
            'hak'               => 'nullable|max:20',
            'file_path'         => 'nullable|mimes:pdf|max:10000',
            'file_photo'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
            'latitude'          => 'nullable|required_with:longitude|max:15',
            'longitude'         => 'nullable|required_with:latitude|max:15',
            'polygon'           => 'nullable|required_with:polygon',
            'id_opd'            => 'nullable|max:11',
        ]);

        // Proses upload file PDF
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('pdfs', 'public');
            $outletData['file_path'] = $filePath;
        }

        // Proses upload file foto
        if ($request->hasFile('file_photo')) {
            $filePhoto = $request->file('file_photo')->store('photos', 'public');
            $outletData['file_photo'] = $filePhoto;
        }

        $outlet->update($outletData);

        return redirect()->route('outlets.show', $outlet);
    }

    /**
     * Remove the specified outlet from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Outlet  $outlet
     * @return \Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, Outlet $outlet)
    {
        $this->authorize('delete', $outlet);

        $request->validate(['outlet_id' => 'required']);

        if ($request->get('outlet_id') == $outlet->id && $outlet->delete()) {
            return redirect()->route('outlets.index');
        }

        return back();
    }
}
