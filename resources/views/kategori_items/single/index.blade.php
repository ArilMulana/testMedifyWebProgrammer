@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{url('kategori-items')}}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
            </div>

            @if(!$data)
                <div class="alert alert-danger">Kategori tidak ditemukan.</div>
            @else
            <div class="card">
                <div class="card-header">Kategori Item</div>

                <div class="card-body">
                    <table>
                        <tr>
                            <th>Kode</th>
                            <td>:</td>
                            <td>{{ $data->kode }}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>:</td>
                            <td>{{ $data->nama }}</td>
                        </tr>
                    </table>

                    <hr>
                    <h5>Item pada Kategori Ini</h5>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Supplier</th>
                                <th>Harga Beli</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data->masterItems as $masterItem)
                                <tr>
                                    <td>{{ $masterItem->kode }}</td>
                                    <td>{{ $masterItem->nama }}</td>
                                    <td>{{ $masterItem->supplier }}</td>
                                    <td>{{ $masterItem->harga_beli }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada item pada kategori ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <a class="btn btn-info" href="{{ url('kategori-items/form/edit') }}/{{ $data->id }}">Edit</a>
                    <a class="btn btn-success" href="{{ url('kategori-items/view/' . $data->id . '/pdf') }}">Download PDF</a>
                    <a class="btn btn-danger" href="{{ url('kategori-items/delete') }}/{{ $data->id }}" onclick="return confirm('Are you sure you want to delete this kategori?');">Delete</a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@section('js')
@endsection
