<?php

namespace App\Http\Controllers\Api;

use App\Opd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OpdController extends Controller
{
    /**
     * Display a listing of OPD (government agencies).
     * GET /api/opd
     */
    public function index(Request $request)
    {
        $query = Opd::query();

        if ($search = $request->get('q')) {
            $query->where('nama_opd', 'like', "%{$search}%")
                  ->orWhere('sub_opd', 'like', "%{$search}%")
                  ->orWhere('upt', 'like', "%{$search}%");
        }

        $perPage = min((int) $request->get('per_page', 25), 100);
        $opd = $query->orderBy('nama_opd')->paginate($perPage);

        return response()->json($opd);
    }

    /**
     * Display all OPD without pagination (for dropdowns/selects).
     * GET /api/opd/all
     */
    public function all()
    {
        $opd = Opd::orderBy('nama_opd')
            ->select('id_opd', 'nama_opd', 'sub_opd', 'upt')
            ->get();

        return response()->json($opd);
    }

    /**
     * Display the specified OPD with its asset count.
     * GET /api/opd/{id}
     */
    public function show($id)
    {
        $opd = Opd::where('id_opd', $id)->first();

        if (!$opd) {
            return response()->json(['message' => 'OPD tidak ditemukan.'], 404);
        }

        $assetCount = \DB::table('outlets')
            ->where('id_opd', $id)
            ->count();

        return response()->json(array_merge(
            $opd->toArray(),
            ['jumlah_aset' => $assetCount]
        ));
    }

    /**
     * Store a newly created OPD.
     * POST /api/opd
     */
    public function store(Request $request)
    {
        $this->authorize('manage_outlet');

        $validated = $request->validate([
            'nama_opd' => 'required|max:255',
            'sub_opd'  => 'nullable|max:255',
            'upt'      => 'nullable|max:255',
        ]);

        $validated['creator_id'] = auth()->id();
        $opd = Opd::create($validated);

        return response()->json($opd, 201);
    }

    /**
     * Update the specified OPD.
     * PUT /api/opd/{id}
     */
    public function update(Request $request, $id)
    {
        $this->authorize('manage_outlet');

        $opd = Opd::where('id_opd', $id)->firstOrFail();

        $validated = $request->validate([
            'nama_opd' => 'sometimes|required|max:255',
            'sub_opd'  => 'nullable|max:255',
            'upt'      => 'nullable|max:255',
        ]);

        $opd->update($validated);

        return response()->json($opd);
    }

    /**
     * Remove the specified OPD.
     * DELETE /api/opd/{id}
     */
    public function destroy($id)
    {
        $this->authorize('manage_outlet');

        $opd = Opd::where('id_opd', $id)->firstOrFail();

        $assetCount = \DB::table('outlets')->where('id_opd', $id)->count();
        if ($assetCount > 0) {
            return response()->json([
                'message' => "OPD tidak bisa dihapus karena masih memiliki {$assetCount} aset terdaftar.",
            ], 422);
        }

        $opd->delete();

        return response()->json(['message' => 'OPD berhasil dihapus.']);
    }
}
