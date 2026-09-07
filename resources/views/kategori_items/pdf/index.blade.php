<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Printout Kategori {{ $kategori->nama }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
            color: #222;
        }
        h2 {
            margin-bottom: 4px;
        }
        table.info {
            margin-bottom: 16px;
        }
        table.info td {
            padding: 2px 6px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
        }
        table.items th, table.items td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: left;
        }
        table.items th {
            background-color: #f0f0f0;
        }
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0;
            right: 0;
            font-size: 10px;
            color: #666;
            text-align: right;
            border-top: 1px solid #ccc;
            padding-top: 4px;
        }
    </style>
</head>
<body>
    <h2>Printout Kategori Item</h2>
    <table class="info">
        <tr>
            <td><strong>Nama Kategori</strong></td>
            <td>:</td>
            <td>{{ $kategori->nama }}</td>
        </tr>
        <tr>
            <td><strong>Kode Kategori</strong></td>
            <td>:</td>
            <td>{{ $kategori->kode }}</td>
        </tr>
    </table>

    <h4>Daftar Item pada Kategori Ini</h4>
    <table class="items">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Item</th>
                <th>Supplier</th>
                <th>Harga Beli</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategori->masterItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->supplier }}</td>
                    <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">Belum ada item pada kategori ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ $printedAt->format('d-m-Y H:i:s') }}
    </div>
</body>
</html>
