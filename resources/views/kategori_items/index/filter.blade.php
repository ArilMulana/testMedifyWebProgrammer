<form action="{{ url('kategori-items') }}" method="GET">
<div id="filter-container">
    <h4>Filter</h4>
    <div class="row">
        <div class="col-6">
            <div class="form-group" id="filter-container">
                <label>Kode</label>
                <input type="text" name="kode" class="form-control" id="filter-kode" value="{{ request('kode') }}">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group" id="filter-container">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" id="filter-nama" value="{{ request('nama') }}">
            </div>
        </div>
    </div>
    <button class="btn btn-primary mt-1">Filter</button>
</div>
</form>
