@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{url('master-items/form/new')}}" class="btn btn-secondary">+ Master Items Baru</a>
                <a href="{{url('master-items/export-excel')}}" class="btn btn-success">Download Excel</a>
            </div>
            <div class="card">
                <div class="card-header">Daftar Master Items</div>
                @if($errorMessage)
                    <div class="alert alert-danger">
                        {{ $errorMessage }}
                    </div>
                @endif
                <div class="card-body">
                    @include('master_items.index.filter')
                    @include('master_items.index.table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@include('master_items.index.js')
@endsection
