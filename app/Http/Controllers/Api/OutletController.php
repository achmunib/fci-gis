<?php

namespace App\Http\Controllers\Api;

use App\Outlet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OutletController extends Controller
{
    /**
     * Display a paginated listing of outlets with OPD data.
     * GET /api/outlets
     */
    public function index(Request $request)
    {
        $query = Outlet::query()
            ->leftJoin('opd', 'opd.id_opd', '=', 'outlets.id_opd')
            ->select('outlets.*', 'opd.nama_opd', 'opd.sub_opd', 'opd.upt');

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('outlets.name', 'like', "%{$search}%")
                  ->orWhere('outlets.id_pemda', 'like', "%{$search}%")
                  ->orWhere('outlets.address', 'like', "%{$search}%")
                  ->orWhere('outlets.kode_barang', 'like', "%{$search}%")
                  ->orWhere('outlets.nomor_sertifikat', 'like', "%{$search}%")
                  ->orWhere('outlets.hak', 'like', "%{$search}%");
            });
        }

        if ($opdId = $request->get('id_opd')) {
            $query->where('outlets.id_opd', $opdId);
        }

        $perPage = min((int) $request->get('per_page', 25), 100);
        $outlets = $query->paginate($perPage);

        return response()->json($outlets);
    }

    /**
     * Get all outlets as GeoJSON FeatureCollection for map consumption.
     * GET /api/outlets/geojson
     */
    public function geojson()
    {
        $outlets = \DB::table('opd')
            ->leftJoin('outlets', 'outlets.id_opd', '=', 'opd.id_opd')
            ->select('opd.nama_opd', 'opd.sub_opd', 'opd.upt', 'outlets.*')
            ->whereNotNull('outlets.polygon')
            ->get();

        $colorMap = [
            'Tanah Usaha'      => 'red',
            'Tanah Bangunan'   => 'blue',
            'Tanah Untuk Jalan'=> 'green',
            'Tanah Untuk Makam'=> 'green',
            'Tanah Kosong'     => 'black',
            'Tanah Lapangan'   => 'orange',
        ];

        $features = $outlets->map(function ($outlet) use ($colorMap) {
            $color = 'yellow';
            foreach ($colorMap as $keyword => $c) {
                if (str_contains($outlet->name ?? '', $keyword)) {
                    $color = $c;
                    break;
                }
            }

            $polygonCoords = $outlet->polygon ? json_decode($outlet->polygon, true) : [];

            return [
                'type'       => 'Feature',
                'geometry'   => [
                    'type'        => 'Polygon',
                    'coordinates' => $polygonCoords,
                ],
                'properties' => [
                    'id'                 => $outlet->id,
                    'name'               => $outlet->name,
                    'id_pemda'           => $outlet->id_pemda,
                    'kode_barang'        => $outlet->kode_barang,
                    'luas'               => $outlet->luas,
                    'harga'              => $outlet->harga,
                    'address'            => $outlet->address,
                    'keterangan'         => $outlet->keterangan,
                    'nomor_sertifikat'   => $outlet->nomor_sertifikat,
                    'tanggal_sertifikat' => $outlet->tanggal_sertifikat,
                    'hak'                => $outlet->hak,
                    'tahun_pengadaan'    => $outlet->tahun_pengadaan,
                    'penggunaan'         => $outlet->penggunaan,
                    'latitude'           => $outlet->latitude,
                    'longitude'          => $outlet->longitude,
                    'nama_opd'           => $outlet->nama_opd,
                    'sub_opd'            => $outlet->sub_opd,
                    'upt'                => $outlet->upt,
                    'color'              => $color,
                    'file_photo_url'     => $outlet->file_photo
                        ? \Storage::url($outlet->file_photo)
                        : null,
                    'detail_url'         => route('outlets.show', ['outlet' => $outlet->id]),
                ],
            ];
        });

        return response()->json([
            'type'     => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    /**
     * Display the specified outlet.
     * GET /api/outlets/{id}
     */
    public function show($id)
    {
        $outlet = \DB::table('outlets')
            ->leftJoin('opd', 'opd.id_opd', '=', 'outlets.id_opd')
            ->select('outlets.*', 'opd.nama_opd', 'opd.sub_opd', 'opd.upt')
            ->where('outlets.id', $id)
            ->first();

        if (!$outlet) {
            return response()->json(['message' => 'Aset tidak ditemukan.'], 404);
        }

        return response()->json($outlet);
    }

    /**
     * Store a newly created outlet.
     * POST /api/outlets
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Outlet);

        $validated = $request->validate([
            'id_pemda'           => 'nullable|max:50',
            'name'               => 'required|max:60',
            'kode_barang'        => 'nullable|max:20',
            'register'           => 'nullable|max:2',
            'luas'               => 'nullable|max:11',
            'tahun_pengadaan'    => 'nullable|max:4',
            'penggunaan'         => 'nullable|max:50',
            'harga'              => 'nullable|max:20',
            'address'            => 'nullable|max:255',
            'keterangan'         => 'nullable|max:255',
            'nomor_sertifikat'   => 'nullable|max:255',
            'tanggal_sertifikat' => 'nullable|max:255',
            'hak'                => 'nullable|max:20',
            'file_path'          => 'nullable|mimes:pdf|max:10000',
            'file_photo'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
            'latitude'           => 'nullable|required_with:longitude|max:15',
            'longitude'          => 'nullable|required_with:latitude|max:15',
            'polygon'            => 'nullable|json',
            'id_opd'             => 'nullable|integer|exists:opd,id_opd',
        ]);

        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('pdfs', 'public');
        }

        if ($request->hasFile('file_photo')) {
            $validated['file_photo'] = $request->file('file_photo')->store('photos', 'public');
        }

        $validated['creator_id'] = auth()->id();
        $outlet = Outlet::create($validated);

        return response()->json($outlet, 201);
    }

    /**
     * Update the specified outlet.
     * PUT /api/outlets/{outlet}
     */
    public function update(Request $request, Outlet $outlet)
    {
        $this->authorize('update', $outlet);

        $validated = $request->validate([
            'id_pemda'           => 'nullable|max:50',
            'name'               => 'sometimes|required|max:60',
            'kode_barang'        => 'nullable|max:20',
            'register'           => 'nullable|max:2',
            'luas'               => 'nullable|max:11',
            'tahun_pengadaan'    => 'nullable|max:4',
            'penggunaan'         => 'nullable|max:50',
            'harga'              => 'nullable|max:20',
            'address'            => 'nullable|max:255',
            'keterangan'         => 'nullable|max:255',
            'nomor_sertifikat'   => 'nullable|max:255',
            'tanggal_sertifikat' => 'nullable|max:255',
            'hak'                => 'nullable|max:20',
            'file_path'          => 'nullable|mimes:pdf|max:10000',
            'file_photo'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
            'latitude'           => 'nullable|required_with:longitude|max:15',
            'longitude'          => 'nullable|required_with:latitude|max:15',
            'polygon'            => 'nullable|json',
            'id_opd'             => 'nullable|integer|exists:opd,id_opd',
        ]);

        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('pdfs', 'public');
        }

        if ($request->hasFile('file_photo')) {
            $validated['file_photo'] = $request->file('file_photo')->store('photos', 'public');
        }

        $outlet->update($validated);

        return response()->json($outlet);
    }

    /**
     * Remove the specified outlet from storage.
     * DELETE /api/outlets/{outlet}
     */
    public function destroy(Outlet $outlet)
    {
        $this->authorize('delete', $outlet);

        $outlet->delete();

        return response()->json(['message' => 'Aset berhasil dihapus.']);
    }
}
