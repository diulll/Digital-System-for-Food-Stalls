<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Kota;
use App\Models\Ongkir;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OngkirController extends Controller
{
    /**
     * Display all ongkir data.
     * 
     * GET /api/v1/ongkir
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $ongkirs = Ongkir::with(['kotaAsal.propinsi', 'kotaTujuan.propinsi'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar tarif ongkir berhasil diambil',
            'data' => $ongkirs,
        ], 200);
    }

    /**
     * Display all tarif with city names (like table view).
     * 
     * GET /api/v1/ongkir/tarif
     * 
     * Returns format:
     * {
     *   "id": 1,
     *   "kota_asal": "Kodya Yogyakarta",
     *   "id_tujuan": 2,
     *   "kota_tujuan": "Bantul",
     *   "tarif_per_kg": 10000
     * }
     * 
     * @return JsonResponse
     */
    public function listTarif(): JsonResponse
    {
        $tarifs = DB::table('ongkirs')
            ->join('kotas as kota_asal', 'ongkirs.kota_asal_id', '=', 'kota_asal.id')
            ->join('kotas as kota_tujuan', 'ongkirs.kota_tujuan_id', '=', 'kota_tujuan.id')
            ->select(
                'ongkirs.id',
                'kota_asal.nama as kota_asal',
                'ongkirs.kota_tujuan_id as id',
                'kota_tujuan.nama as kota_tujuan',
                'ongkirs.tarif_per_kg'
            )
            ->orderBy('ongkirs.id')
            ->get();

        // Format ulang untuk sesuai dengan gambar tabel
        $formattedTarifs = $tarifs->map(function ($item) {
            return [
                'id' => $item->id,
                'kota_asal' => $item->kota_asal,
                'id_tujuan' => $item->id,
                'kota_tujuan' => $item->kota_tujuan,
                'tarif_per_kg' => (int) $item->tarif_per_kg,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar tarif ongkir berhasil diambil',
            'data' => $formattedTarifs,
        ], 200);
    }

    /**
     * Get all kota data.
     * 
     * GET /api/v1/kota
     * 
     * @return JsonResponse
     */
    public function getKota(): JsonResponse
    {
        $kotas = Kota::with('propinsi')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar kota berhasil diambil',
            'data' => $kotas,
        ], 200);
    }

    /**
     * Get tarif by kota asal and kota tujuan.
     * 
     * GET /api/tarif/{kota_asal_id}/{kota_tujuan_id}
     * 
     * Example: GET /api/tarif/3/8 (Sleman -> Mojokerto)
     * 
     * @param int $kotaAsalId
     * @param int $kotaTujuanId
     * @return JsonResponse
     */
    public function getTarifByRoute(int $kotaAsalId, int $kotaTujuanId): JsonResponse
    {
        // Cari tarif berdasarkan kota asal dan tujuan
        $tarif = DB::table('ongkirs')
            ->join('kotas as kota_asal', 'ongkirs.kota_asal_id', '=', 'kota_asal.id')
            ->join('kotas as kota_tujuan', 'ongkirs.kota_tujuan_id', '=', 'kota_tujuan.id')
            ->where('ongkirs.kota_asal_id', $kotaAsalId)
            ->where('ongkirs.kota_tujuan_id', $kotaTujuanId)
            ->select(
                'ongkirs.id',
                'kota_asal.id as kota_asal_id',
                'kota_asal.nama as kota_asal',
                'kota_tujuan.id as kota_tujuan_id',
                'kota_tujuan.nama as kota_tujuan',
                'ongkirs.tarif_per_kg'
            )
            ->first();

        if (!$tarif) {
            return response()->json([
                'success' => false,
                'message' => 'Tarif untuk rute ini tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tarif berhasil ditemukan',
            'data' => [
                'id' => $tarif->id,
                'kota_asal_id' => $tarif->kota_asal_id,
                'kota_asal' => $tarif->kota_asal,
                'kota_tujuan_id' => $tarif->kota_tujuan_id,
                'kota_tujuan' => $tarif->kota_tujuan,
                'tarif_per_kg' => (int) $tarif->tarif_per_kg,
            ],
        ], 200);
    }

    /**
     * Calculate shipping cost.
     * 
     * POST /api/v1/ongkir/calculate
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function calculate(Request $request): JsonResponse
    {
        $request->validate([
            'kota_asal_id' => 'required|exists:kotas,id',
            'kota_tujuan_id' => 'required|exists:kotas,id',
            'berat_kg' => 'required|numeric|min:0.1',
        ]);

        $ongkir = Ongkir::getTarif($request->kota_asal_id, $request->kota_tujuan_id);

        if (!$ongkir) {
            return response()->json([
                'success' => false,
                'message' => 'Tarif ongkir untuk rute ini tidak tersedia',
            ], 404);
        }

        $totalOngkir = $ongkir->hitungOngkir($request->berat_kg);

        // Load relasi untuk response
        $ongkir->load(['kotaAsal.propinsi', 'kotaTujuan.propinsi']);

        return response()->json([
            'success' => true,
            'message' => 'Perhitungan ongkir berhasil',
            'data' => [
                'kota_asal' => $ongkir->kotaAsal->nama,
                'kota_tujuan' => $ongkir->kotaTujuan->nama,
                'berat_kg' => $request->berat_kg,
                'tarif_per_kg' => $ongkir->tarif_per_kg,
                'total_ongkir' => $totalOngkir,
            ],
        ], 200);
    }

    /**
     * Store a new ongkir tarif.
     * 
     * POST /api/v1/ongkir
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'kota_asal_id' => 'required|exists:kotas,id',
            'kota_tujuan_id' => 'required|exists:kotas,id',
            'tarif_per_kg' => 'required|numeric|min:0',
        ]);

        // Check if tarif already exists
        $existing = Ongkir::where('kota_asal_id', $request->kota_asal_id)
            ->where('kota_tujuan_id', $request->kota_tujuan_id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Tarif untuk rute ini sudah ada',
            ], 422);
        }

        $ongkir = Ongkir::create($request->all());
        $ongkir->load(['kotaAsal.propinsi', 'kotaTujuan.propinsi']);

        return response()->json([
            'success' => true,
            'message' => 'Tarif ongkir berhasil ditambahkan',
            'data' => $ongkir,
        ], 201);
    }

    /**
     * Update ongkir tarif.
     * 
     * PUT /api/v1/ongkir/{id}
     * 
     * @param Request $request
     * @param Ongkir $ongkir
     * @return JsonResponse
     */
    public function update(Request $request, Ongkir $ongkir): JsonResponse
    {
        $request->validate([
            'tarif_per_kg' => 'required|numeric|min:0',
        ]);

        $ongkir->update([
            'tarif_per_kg' => $request->tarif_per_kg,
        ]);

        $ongkir->load(['kotaAsal.propinsi', 'kotaTujuan.propinsi']);

        return response()->json([
            'success' => true,
            'message' => 'Tarif ongkir berhasil diupdate',
            'data' => $ongkir,
        ], 200);
    }

    /**
     * Delete ongkir tarif.
     * 
     * DELETE /api/v1/ongkir/{id}
     * 
     * @param Ongkir $ongkir
     * @return JsonResponse
     */
    public function destroy(Ongkir $ongkir): JsonResponse
    {
        $ongkir->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tarif ongkir berhasil dihapus',
        ], 200);
    }
}
