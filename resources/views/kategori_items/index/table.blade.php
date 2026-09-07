
<table id="table" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Jumlah Item</th>
            <th>View</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($items as $item )
            <tr>
                <td>{{ $item['kode'] }}</td>
                <td>{{ $item['nama'] }}</td>
                <td>{{ $item['master_items_count'] ?? '-' }}</td>
                <td class="text-center">
                    <a href="{{ url('kategori-items/view/' . $item['id']) }}" class="btn btn-primary btn-sm">
                        View
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted py-4">Data kategori tidak ditemukan.</td>
            </tr>
        @endforelse
    </tbody>
</table>
