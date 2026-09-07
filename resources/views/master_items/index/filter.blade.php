<form action="{{ url('master-items') }}" method="GET">
<div id="filter-container">
    <h4>Filter</h4>
    <div class="row">
        <div class="col-4">
            <div class="form-group" id="filter-container">
                <label>Kode</label>
                <input type="text" name="kode" class="form-control" id="filter-kode" value="{{ request('kode') }}">
            </div>
        </div>
        <div class="col-4">
            <div class="form-group" id="filter-container">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" id="filter-nama" value="{{ request('nama') }}">
            </div>
        </div>
        <div class="col-2">
            <div class="form-group" id="filter-container">
                <label>Harga Min</label>
                <input type="number" name="harga_min" class="form-control" id="filter-harga-min" value="{{ request('harga_min') }}">
            </div>
        </div>
        <div class="col-2">
            <div class="form-group" id="filter-container">
                <label>Harga Max</label>
                <input type="number" name="harga_max" class="form-control" id="filter-harga-max" value="{{ request('harga_max') }}">
            </div>
        </div>
    </div>
    <button class="btn btn-primary mt-1 btn-get-data">Filter</button>
    <span id="loading-filter" style="display: none;">Loading...</span>
</div>
</form>
