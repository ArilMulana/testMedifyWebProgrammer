<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MasterItemAPIResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'kode' => $this->kode,
            'nama' => $this->nama,
            'harga_beli' => $this->harga_beli,
            'laba' => $this->laba,
            'supplier' => $this->supplier,
            'jenis' => $this->jenis,
            'foto' => $this->foto,
            'foto_url' => $this->foto ? asset('storage/' . $this->foto) : null,
            // Nama-nama kategori yang dimiliki item ini (hasil eager loading di controller).
            'kategori' => $this->whenLoaded('kategoriItems', function () {
                return $this->kategoriItems->map(function ($kategori) {
                    return [
                        'id' => $kategori->id,
                        'kode' => $kategori->kode,
                        'nama' => $kategori->nama,
                    ];
                });
            }),
        ];
    }
}
