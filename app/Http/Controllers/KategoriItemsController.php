<?php

namespace App\Http\Controllers;

use App\Models\KategoriItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route as FacadesRoute;

class KategoriItemsController extends Controller
{
    public function index(Request $request)
    {
        $errorMessage = null;
        $items = [];
        try {
            $queryParams = $request->only(['kode', 'nama']);
            $apiRequest = Request::create('/api/kategori-items', 'GET', $queryParams);
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

        return view('kategori_items.index.index', compact('items', 'errorMessage'));
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = new KategoriItem();
        } else {
            $item = KategoriItem::find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        return view('kategori_items.form.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        try {
            $payload = $request->except(['_token']);

            if ($method == 'new') {
                $apiRequest = Request::create('/api/kategori-items', 'POST', $payload);
            } else {
                $apiRequest = Request::create("/api/kategori-items/{$id}", 'PUT', $payload);
            }

            $apiRequest->headers->set('Accept', 'application/json');

            $apiResponse = FacadesRoute::dispatch($apiRequest);
            $responseData = json_decode($apiResponse->getContent(), true);

            if ($apiResponse->isSuccessful() && isset($responseData['success']) && $responseData['success'] === true) {
                return redirect('kategori-items')->with('success', $responseData['message'] ?? 'Data berhasil disimpan!');
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

    /**
     * Halaman detail (single view) kategori, menampilkan nama, kode,
     * dan daftar item (master items) yang memiliki kategori tersebut.
     */
    public function singleView($id)
    {
        // Eager load masterItems agar tabel item tidak menyebabkan N+1 query.
        $data['data'] = KategoriItem::with('masterItems')->find($id);
        return view('kategori_items.single.index', $data);
    }

    public function delete($id)
    {
        $item = KategoriItem::find($id);
        if ($item) {
            $item->masterItems()->detach();
            $item->delete();
        }
        return redirect('kategori-items');
    }

    /**
     * Unduh printout (PDF) dari halaman detail kategori.
     * Berisi: nama kategori, kode kategori, tabel item yang memiliki
     * kategori tersebut, dan tanggal/waktu cetak pada footer.
     */
    public function exportPdf($id)
    {
        $kategori = KategoriItem::with('masterItems')->findOrFail($id);

        $pdf = Pdf::loadView('kategori_items.pdf.index', [
            'kategori' => $kategori,
            'printedAt' => now(),
        ])->setPaper('a4', 'portrait');

        $filename = 'kategori-' . str($kategori->kode)->slug() . '.pdf';

        return $pdf->download($filename);
    }
}
