@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{url('kategori-items/form/new')}}" class="btn btn-secondary">+ Kategori Item Baru</a>
            </div>
            <div class="card">
                <div class="card-header">Daftar Kategori Items</div>
                @if(session('success'))
                    <div class="alert alert-success mb-0">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger mb-0">{{ session('error') }}</div>
                @endif
                @if($errorMessage)
                    <div class="alert alert-danger">
                        {{ $errorMessage }}
                    </div>
                @endif
                <div class="card-body">
                    @include('kategori_items.index.filter')
                    @include('kategori_items.index.table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@include('kategori_items.index.js')
@endsection
