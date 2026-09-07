
<table id="table" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Foto</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Jenis</th>
            <th>Kategori</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Supplier</th>
            <th>View</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($items as $item )
        @php
            // Hitung harga_jual berdasarkan formula matematika
            $hargaBeli = $item['harga_beli'] ?? 0;
            $laba = $item['laba'] ?? 0;
            $hargaJual = round($hargaBeli + ($hargaBeli * $laba / 100));
            $namaKategori = collect($item['kategori'] ?? [])->pluck('nama')->implode(', ');
        @endphp
            <tr>
                <td class="text-center">
                    @if(!empty($item['foto_url']))
                        <img src="{{ $item['foto_url'] }}" alt="{{ $item['nama'] }}" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>{{$item['kode']  }}</td>
                <td>{{$item['nama']  }}</td>
                <td>{{$item['jenis']  }}</td>
                <td>{{$namaKategori ?: '-' }}</td>
                <td>{{$item['harga_beli']  }}</td>
                <td>{{$hargaJual }}</td>
                <td>{{$item['supplier']  }}</td>
                <td class="text-center">
                    <a href="{{ url('master-items/view/' . $item['kode']) }}" class="btn btn-primary btn-sm">
                        View
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center text-muted py-4">Data item tidak ditemukan.</td>
            </tr>
        @endforelse
    </tbody>
</table>
