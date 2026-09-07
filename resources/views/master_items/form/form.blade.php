<form method="POST" enctype="multipart/form-data">
    @csrf
    @if($method == 'edit')
    <div class="form-group">
        <label>Kode Barang</label>
        <input type="text" class="form-control" name="kode_barang" required readonly value="{{$item->kode ?? ''}}">
    </div>
    @endif

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required value="{{ old('nama', $item->nama ?? '') }}">
    </div>

    <div class="form-group">
        <label>Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required value="{{ old('harga_beli', $item->harga_beli ?? '') }}">
    </div>

    <div class="form-group">
        <label>Laba (dalam persen)</label>
        <input type="number" class="form-control" name="laba" required value="{{ old('laba', $item->laba ?? '') }}">
    </div>

    @php $selected = old('supplier', $item->supplier ?? ''); @endphp
    <div class="form-group">
        <label>Supplier</label>
        <select class="form-control" required name="supplier">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Tokopaedi') selected @endif>Tokopaedi</option>
            <option @if($selected == 'Bukulapuk') selected @endif>Bukulapuk</option>
            <option @if($selected == 'TokoBagas') selected @endif>TokoBagas</option>
            <option @if($selected == 'E Commurz') selected @endif>E Commurz</option>
            <option @if($selected == 'Blublu') selected @endif>Blublu</option>
        </select>
    </div>

    @php $selected = old('jenis', $item->jenis ?? ''); @endphp
    <div class="form-group">
        <label>Jenis</label>
        <select class="form-control" required name="jenis">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Obat') selected @endif>Obat</option>
            <option @if($selected == 'Alkes') selected @endif>Alkes</option>
            <option @if($selected == 'Matkes') selected @endif>Matkes</option>
            <option @if($selected == 'Umum') selected @endif>Umum</option>
            <option @if($selected == 'ATK') selected @endif>ATK</option>
        </select>
    </div>

    <div class="form-group">
        <label>Kategori</label>
        <div class="border rounded p-2">
            @forelse($kategoriList as $kategori)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="kategori_ids[]"
                        value="{{ $kategori->id }}" id="kategori-{{ $kategori->id }}"
                        @checked(in_array($kategori->id, $selectedKategoriIds ?? []))>
                    <label class="form-check-label" for="kategori-{{ $kategori->id }}">
                        {{ $kategori->nama }} ({{ $kategori->kode }})
                    </label>
                </div>
            @empty
                <span class="text-muted">Belum ada Kategori Item. <a href="{{ url('kategori-items/form/new') }}">Buat kategori baru</a>.</span>
            @endforelse
        </div>
    </div>

    <div class="form-group">
        <label>Foto</label>
        @if(!empty($item->foto))
            <div class="mb-2">
                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" style="width:100px;height:100px;object-fit:cover;border-radius:4px;">
            </div>
        @endif
        <input type="file" class="form-control" name="foto" accept="image/*">
        @if($method == 'edit')
            <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
        @endif
    </div>

    <button class="btn btn-primary mt-3">Submit</button>

</form>
