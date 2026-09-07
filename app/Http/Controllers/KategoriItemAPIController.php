<?php

namespace App\Http\Controllers;

use App\Models\KategoriItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class KategoriItemAPIController extends Controller
{
    /**
     * Display a listing of the resource, dengan filter nama dan kode.
     */
    public function index(Request $request): JsonResponse
    {
        // withCount menghindari perlunya query terpisah per baris untuk menghitung jumlah item.
        $query = KategoriItem::withCount('masterItems');

        if ($request->filled('kode')) {
            $query->where('kode', 'like', '%' . $request->kode . '%');
        }

        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        $data = $query->orderBy('id')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data Success',
            'data' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = FacadesValidator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:kategori_items,kode',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $kategori = new KategoriItem();
            $kategori->kode = $request->kode;
            $kategori->nama = $request->nama;
            $kategori->save();

            return response()->json([
                'success' => true,
                'message' => 'Kategori Item berhasil ditambahkan',
                'data' => $kategori,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data ke database: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $kategori = KategoriItem::find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori Item tidak ditemukan',
            ], 404);
        }

        $validator = FacadesValidator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:kategori_items,kode,' . $kategori->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {

            $kategori->kode = $request->kode;
            $kategori->nama = $request->nama;
            $kategori->save();

            return response()->json([
                'success' => true,
                'message' => 'Kategori Item berhasil diperbarui',
                'data' => $kategori,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
