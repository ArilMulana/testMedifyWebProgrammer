<form method="POST">
    @csrf

    <div class="form-group">
        <label>Kode Kategori</label>
        <input type="text" class="form-control" name="kode" required value="{{ old('kode', $item->kode ?? '') }}">
        @error('kode')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text" class="form-control" name="nama" required value="{{ old('nama', $item->nama ?? '') }}">
        @error('nama')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <button class="btn btn-primary mt-3">Submit</button>

</form>
