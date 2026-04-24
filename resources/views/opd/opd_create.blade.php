@extends('layouts.app')

@section('title', __('outlet.create'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('outlet.create') }}</div>
            <form method="POST" action="{{ route('opd_store') }}" accept-charset="UTF-8">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_opd" class="control-label">NAMA OPD</label>
                                <input id="nama_opd" type="text" class="form-control{{ $errors->has('nama_opd') ? ' is-invalid' : '' }}" name="nama_opd" value="{{ old('nama_opd') }}" required>
                                {!! $errors->first('nama_opd', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sub_opd" class="control-label">SUB OPD</label>
                                <input id="sub_opd" type="text" class="form-control{{ $errors->has('sub_opd') ? ' is-invalid' : '' }}" name="sub_opd" value="{{ old('sub_opd') }}" required>
                                {!! $errors->first('sub_opd', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>   
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="upt" class="control-label">UPT</label>
                                <input id="upt" type="text" class="form-control{{ $errors->has('upt') ? ' is-invalid' : '' }}" name="upt" value="{{ old('upt') }}" required>
                                {!! $errors->first('upt', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="card-footer">
                    <input type="submit" value="{{ __('outlet.create') }}" class="btn btn-success">
                    <a href="{{ route('opd_data') }}" class="btn btn-link">BACK</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
    integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
    crossorigin=""/>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

<style>
    #mapid { height: 300px; }
</style>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
    integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
    crossorigin="">
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
@endpush
