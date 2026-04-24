@extends('layouts.app')

@section('title', __('outlet.create'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ __('outlet.create') }}</div>
            <form method="POST" action="{{ route('outlets.store') }}" enctype="multipart/form-data" accept-charset="UTF-8">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_pemda" class="control-label">{{ __('outlet.id_pemda') }}</label>
                                <input id="id_pemda" type="text" class="form-control{{ $errors->has('id_pemda') ? ' is-invalid' : '' }}" name="id_pemda" value="{{ old('id_pemda') }}" required>
                                {!! $errors->first('id_pemda', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="control-label">{{ __('outlet.name') }}</label>
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required>
                                {!! $errors->first('name', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>   
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_barang" class="control-label">{{ __('outlet.kode_barang') }}</label>
                                <input id="kode_barang" type="text" class="form-control{{ $errors->has('kode_barang') ? ' is-invalid' : '' }}" name="kode_barang" value="{{ old('kode_barang') }}" required>
                                {!! $errors->first('kode_barang', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                                <label for="register" class="control-label">{{ __('outlet.register') }}</label>
                                <input id="register" type="text" class="form-control{{ $errors->has('register') ? ' is-invalid' : '' }}" name="register" value="{{ old('register') }}" required>
                                {!! $errors->first('register', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="luas" class="control-label">{{ __('outlet.luas') }}</label>
                                <input id="luas" type="number" class="form-control{{ $errors->has('luas') ? ' is-invalid' : '' }}" name="luas" value="{{ old('luas') }}" required>
                                {!! $errors->first('luas', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tahun_pengadaan" class="control-label">{{ __('outlet.tahun_pengadaan') }}</label>
                                <input id="tahun_pengadaan" type="text" class="form-control{{ $errors->has('tahun_pengadaan') ? ' is-invalid' : '' }}" name="tahun_pengadaan" value="{{ old('tahun_pengadaan') }}" required>
                                {!! $errors->first('tahun_pengadaan', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>  
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="penggunaan" class="control-label">{{ __('outlet.penggunaan') }}</label>
                                <input id="penggunaan" type="text" class="form-control{{ $errors->has('penggunaan') ? ' is-invalid' : '' }}" name="penggunaan" value="{{ old('penggunaan') }}" required>
                                {!! $errors->first('penggunaan', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="harga" class="control-label">{{ __('outlet.harga') }}</label>
                                <input id="harga" type="number" class="form-control{{ $errors->has('harga') ? ' is-invalid' : '' }}" name="harga" value="{{ old('harga') }}" required>
                                {!! $errors->first('harga', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div> 
                        </div>
                        <div class="form-group">
                            <label for="address" class="control-label">{{ __('outlet.address') }}</label>
                            <textarea id="address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" rows="4">{{ old('address') }}</textarea>
                            {!! $errors->first('address', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="control-label">{{ __('outlet.keterangan') }}</label>
                            <textarea id="keterangan" class="form-control{{ $errors->has('keterangan') ? ' is-invalid' : '' }}" name="keterangan" rows="4">{{ old('keterangan') }}</textarea>
                            {!! $errors->first('keterangan', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="polygon" class="control-label">{{ __('outlet.polygon') }}</label>
                                <input id="polygon" type="text" class="form-control{{ $errors->has('latitude') ? ' is-invalid' : '' }}" name="polygon" value="{{ old('polygon', request('polygon')) }}" required>
                                {!! $errors->first('polygon', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_opd" class="control-label">{{ __('outlet.id_opd') }}</label>
                                    <select class="form-control" id="id_opd" name="id_opd">
                                            @foreach($outlets as $data)
                                            <option value="{{ $data->id_opd }}">{{ $data->nama_opd }}</option>
                                            @endforeach
                                    </select>
                                {!! $errors->first('id_opd', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nomor_sertifikat" class="control-label">Nomer Sertifikat</label>
                                <input id="nomor_sertifikat" type="text" class="form-control{{ $errors->has('nomor_sertifikat') ? ' is-invalid' : '' }}" name="nomor_sertifikat" value="{{ old('nomor_sertifikat') }}" required>
                                {!! $errors->first('nomor_sertifikat', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_sertifikat" class="control-label">Tanggal Sertifikat</label>
                                <input id="tanggal_sertifikat" type="date" class="form-control{{ $errors->has('tanggal_sertifikat') ? ' is-invalid' : '' }}" name="tanggal_sertifikat" value="{{ old('tanggal_sertifikat') }}" required>
                                {!! $errors->first('tanggal_sertifikat', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hak" class="control-label">Hak</label>
                                <input id="hak" type="text" class="form-control{{ $errors->has('hak') ? ' is-invalid' : '' }}" name="hak" value="{{ old('hak') }}" required>
                                {!! $errors->first('hak', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="file_path" class="form-label">File Sertifikat:</label>
                                <input id="file_path" type="file" name="file_path" class="form-control{{ $errors->has('file_path') ? ' is-invalid' : '' }}" value="{{ old('file_path') }}" required>
                                {!! $errors->first('file_path', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="file_photo" class="form-label">File Foto:</label>
                                <input id="file_photo" type="file" name="file_photo" class="form-control{{ $errors->has('file_photo') ? ' is-invalid' : '' }}" value="{{ old('file_photo') }}" required>
                                {!! $errors->first('file_photo', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="latitude" class="control-label">{{ __('outlet.latitude') }}</label>
                                <input id="latitude" type="text" class="form-control{{ $errors->has('latitude') ? ' is-invalid' : '' }}" name="latitude" value="{{ old('latitude', request('latitude')) }}" required>
                                {!! $errors->first('latitude', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="longitude" class="control-label">{{ __('outlet.longitude') }}</label>
                                <input id="longitude" type="text" class="form-control{{ $errors->has('longitude') ? ' is-invalid' : '' }}" name="longitude" value="{{ old('longitude', request('longitude')) }}" required>
                                {!! $errors->first('longitude', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div> -->
                    </div>
                    <div id="mapid"></div>
                </div>
                <div class="card-footer">
                    <input type="submit" value="{{ __('outlet.create') }}" class="btn btn-success">
                    <a href="{{ route('outlets.index') }}" class="btn btn-link">{{ __('app.cancel') }}</a>
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
<link rel="stylesheet" href="https://js.arcgis.com/4.24/esri/themes/light/main.css">

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
<script src="https://js.arcgis.com/4.24/"></script>
<script>
    var mapCenter = [{{ request('latitude', config('leaflet.map_center_latitude')) }}, {{ request('longitude', config('leaflet.map_center_longitude')) }}];
    var map = L.map('mapid').setView(mapCenter, {{ config('leaflet.zoom_level') }});

        // Tambahkan layer satelit
    var layer_satelit = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; <a href="https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer">Esri</a>'
    }).addTo(map);

    // Tambahkan layer nama jalan
    var layer_jalan = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; <a href="https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer">Esri</a>'
    }).addTo(map);
    
    var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);
        if($("[name=polygon]").val()!=""){
            var latlngs = JSON.parse($("[name=polygon]").val());
            var polygon = L.polygon(latlngs, {color: 'red'}).addTo(drawnItems);
        }

    var drawControl = new L.Control.Draw({
        draw:{
            polyline:true,
            rectangle:true,
            circle:true,
            marker:true,
            circlemarker:true
        },
        edit: {
            featureGroup: drawnItems
        }
     });
     map.addControl(drawControl);
     map.on('draw:created', function (e) {
        
        console.log("Created")
        
        var type = e.layerType,
           layer = e.layer;
        var latLng=layer.getLatLngs();
        console.log(latLng);

        $("[name=polygon]").val(JSON.stringify(latLng));
        console.log(polygon);
        drawnItems.addLayer(layer);
    });

    map.on('draw:edited',function(e){
        console.log('edited');
        var latLng=e.layers.getLayers()[0].getLatLngs()[0];
        
    $("[name=polygon]").val(JSON.stringify(latLng));
        })
    
    map.on('draw:deleted',function(e){
        console.log('deleted');
        
        $("[name=polygon]").val("");
     })
</script>
@endpush
