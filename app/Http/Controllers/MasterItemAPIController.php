<?php

namespace App\Http\Controllers;

use App\Http\Resources\MasterItemAPIResource;
use App\Models\MasterItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class MasterItemAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Eager load relasi kategoriItems agar tidak terjadi N+1 query (sesuai anjuran eager loading).
        $query = MasterItem::with('kategoriItems');

        // Filter berdasarkan Kode
        if ($request->filled('kode')) {
            $query->where('kode', 'like', '%' . $request->kode . '%');
        }

        // Filter berdasarkan Nama
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        // Filter berdasarkan Harga Beli Min
        if ($request->filled('harga_min')) {
            $query->where('harga_beli', '>=', $request->harga_min);
        }

        // Filter berdasarkan Harga Beli Max
        if ($request->filled('harga_max')) {
            $query->where('harga_beli', '<=', $request->harga_max);
        }

        $data = $query->orderBy('id')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data Success',
            'data' => MasterItemAPIResource::collection($data)
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // 1. Validasi Input
        $fotoRules = $request->hasFile('foto')
            ? 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
            : 'nullable|string';

        $validator = FacadesValidator::make($request->all(), [
            'nama'          => 'required|string|max:255',
            'harga_beli'    => 'required|integer|min:0',
            'laba'          => 'required|integer|min:0|max:100',
            'supplier'      => 'required|string|max:255',
            'jenis'         => 'required|string|max:255',
            'foto'          => $fotoRules,
            'kategori_ids'  => 'nullable|array',
            'kategori_ids.*' => 'integer|exists:kategori_items,id',
        ]);

        // 2. Jika Validasi Gagal (HTTP 422)
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $totalData = MasterItem::count('id');
            $nextNumber = $totalData + 1;
            $kode = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $item = new MasterItem();
            $item->kode       = $kode;
            $item->nama       = $request->nama;
            $item->harga_beli = $request->harga_beli;
            $item->laba       = $request->laba;
            $item->supplier   = $request->supplier;
            $item->jenis      = $request->jenis;

            if ($request->hasFile('foto')) {
                $item->foto = $request->file('foto')->store('master-items', 'public');
            } else {
                $item->foto = $request->foto;
            }

            $item->save();

            if ($request->has('kategori_ids')) {
                $item->kategoriItems()->sync($request->kategori_ids ?? []);
            }

            return response()->json([
                'success' => true,
                'message' => 'Master Item berhasil ditambahkan',
                'data'    => $item->load('kategoriItems')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data ke database: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $item = MasterItem::find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Master Item tidak ditemukan',
            ], 404);
        }

        $validator = FacadesValidator::make($request->all(), [
            'nama'          => 'required|string|max:255',
            'harga_beli'    => 'required|integer|min:0',
            'laba'          => 'required|integer|min:0|max:100',
            'supplier'      => 'required|string|max:255',
            'jenis'         => 'required|string|max:255',
            'foto'          => 'nullable|string',
            'kategori_ids'  => 'nullable|array',
            'kategori_ids.*' => 'integer|exists:kategori_items,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $item->nama       = $request->nama;
            $item->harga_beli = $request->harga_beli;
            $item->laba       = $request->laba;
            $item->supplier   = $request->supplier;
            $item->jenis      = $request->jenis;

            // Foto hanya diganti jika ada file baru yang diunggah (dikirim sebagai path oleh controller web)
            if ($request->filled('foto')) {
                $item->foto = $request->foto;
            }

            $item->save();

            if ($request->has('kategori_ids')) {
                $item->kategoriItems()->sync($request->kategori_ids ?? []);
            }

            return response()->json([
                'success' => true,
                'message' => 'Master Item berhasil diperbarui',
                'data'    => $item->load('kategoriItems')
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
