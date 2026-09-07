<?php

namespace App\Http\Controllers;

use App\Models\KategoriItem;
use App\Models\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Illuminate\Support\Facades\Storage;

class MasterItemsController extends Controller
{
    public function index(Request $request)
    {
        $errorMessage = null;
        $items = [];
        try {
            $queryParams = $request->only(['kode', 'nama', 'harga_min', 'harga_max']);
            $apiRequest = Request::create('/api/master-items', 'GET', $queryParams);
            $apiResponse = FacadesRoute::dispatch($apiRequest);
            $responseData = json_decode($apiResponse->getContent(), true);

            if ($apiResponse->isSuccessful() && isset($responseData['success']) && $responseData['success'] === true) {
                $items = $responseData['data'];
            } else {
                $errorMessage = $responseData['message'] ?? 'Gagal mengambil data dari API.';
            }
        } catch (\Exception $e) {
            $errorMessage = ' Tidak dapat terhubung ke server API ' . $e->getMessage();
        }

        return view('master_items.index.index', compact('items', 'errorMessage'));
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = new MasterItem();
            $selectedKategoriIds = [];
        } else {
            $item = MasterItem::with('kategoriItems')->find($id);
            $selectedKategoriIds = $item ? $item->kategoriItems->pluck('id')->toArray() : [];
        }

        // Gunakan eager loading (bukan lazy load di view) untuk daftar kategori pada dropdown/checkbox.
        $kategoriList = KategoriItem::orderBy('nama')->get();

        $data['item'] = $item;
        $data['method'] = $method;
        $data['kategoriList'] = $kategoriList;
        $data['selectedKategoriIds'] = $selectedKategoriIds;
        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::with('kategoriItems')->where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        try {
            $payload = $request->except(['foto', '_token']);

            // Tangani upload foto (field baru): simpan ke disk publik jika ada file yang diunggah.
            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                $path = $request->file('foto')->store('master-items', 'public');
                $payload['foto'] = $path;

                // Hapus foto lama saat update agar tidak menumpuk file yang tidak terpakai.
                if ($method != 'new') {
                    $oldItem = MasterItem::find($id);
                    if ($oldItem && $oldItem->foto && Storage::disk('public')->exists($oldItem->foto)) {
                        Storage::disk('public')->delete($oldItem->foto);
                    }
                }
            }

            if ($method == 'new') {
                $apiRequest = Request::create('/api/master-items', 'POST', $payload);
            } else {
                $apiRequest = Request::create("/api/master-items/{$id}", 'PUT', $payload);
            }

            $apiRequest->headers->set('Accept', 'application/json');

            $apiResponse = FacadesRoute::dispatch($apiRequest);
            $responseData = json_decode($apiResponse->getContent(), true);

            if ($apiResponse->isSuccessful() && isset($responseData['success']) && $responseData['success'] === true) {
                return redirect('master-items')->with('success', $responseData['message'] ?? 'Data berhasil disimpan!');
            }

            if ($apiResponse->getStatusCode() === 422 && isset($responseData['errors'])) {
                return redirect()->back()
                    ->withErrors($responseData['errors'])
                    ->withInput();
            }

            return redirect()->back()
                ->with('error', $responseData['message'] ?? 'Gagal menyimpan data via API.')
                ->withInput();

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function delete($id)
    {
        $item = MasterItem::find($id);
        if ($item) {
            if ($item->foto && Storage::disk('public')->exists($item->foto)) {
                Storage::disk('public')->delete($item->foto);
            }
            $item->delete();
        }
        return redirect('master-items');
    }

    /**
     * Unduh data Master Items dalam bentuk file Excel.
     *
     * Kolom: No, Nama kategori (dipisah koma), Nama items, Nama supplier, Harga, Laba, Harga jual.
     */
    public function exportExcel()
    {
        // Eager load kategoriItems untuk menghindari N+1 query saat menyusun kolom "Nama kategori".
        $items = MasterItem::with('kategoriItems')->orderBy('id')->get();

        $filename = 'master-items-' . now()->format('Ymd-His') . '.xls';

        $rows = '';
        $no = 1;
        foreach ($items as $item) {
            $namaKategori = $item->kategoriItems->pluck('nama')->implode(', ');
            $hargaJual = round($item->harga_beli + ($item->harga_beli * $item->laba / 100));

            $rows .= '<Row>'
                . '<Cell><Data ss:Type="Number">' . $no . '</Data></Cell>'
                . '<Cell><Data ss:Type="String">' . e($namaKategori) . '</Data></Cell>'
                . '<Cell><Data ss:Type="String">' . e($item->nama) . '</Data></Cell>'
                . '<Cell><Data ss:Type="String">' . e($item->supplier) . '</Data></Cell>'
                . '<Cell><Data ss:Type="Number">' . (int) $item->harga_beli . '</Data></Cell>'
                . '<Cell><Data ss:Type="Number">' . (int) $item->laba . '</Data></Cell>'
                . '<Cell><Data ss:Type="Number">' . (int) $hargaJual . '</Data></Cell>'
                . '</Row>';
            $no++;
        }

        $header = '<Row>'
            . '<Cell><Data ss:Type="String">No</Data></Cell>'
            . '<Cell><Data ss:Type="String">Nama Kategori</Data></Cell>'
            . '<Cell><Data ss:Type="String">Nama Items</Data></Cell>'
            . '<Cell><Data ss:Type="String">Nama Supplier</Data></Cell>'
            . '<Cell><Data ss:Type="String">Harga</Data></Cell>'
            . '<Cell><Data ss:Type="String">Laba</Data></Cell>'
            . '<Cell><Data ss:Type="String">Harga Jual</Data></Cell>'
            . '</Row>';

        // Menggunakan format SpreadsheetML (Excel XML) bawaan Laravel/PHP tanpa dependensi tambahan,
        // sehingga file dapat dibuka langsung oleh Microsoft Excel / LibreOffice Calc.
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<?mso-application progid="Excel.Sheet"?>'
            . '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" '
            . 'xmlns:o="urn:schemas-microsoft-com:office:office" '
            . 'xmlns:x="urn:schemas-microsoft-com:office:excel" '
            . 'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">'
            . '<Worksheet ss:Name="Master Items">'
            . '<Table>' . $header . $rows . '</Table>'
            . '</Worksheet>'
            . '</Workbook>';

        return response($xml, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach ($data as $item) {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100, 1000000);
            $item->laba = rand(10, 99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
        $random = rand(0, 4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        $random = rand(0, 4);
        return $array[$random];
    }
}
