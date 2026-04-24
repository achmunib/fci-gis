@extends('layouts.app')

@section('title', __('outlet.edit'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        @if (request('action') == 'delete' && $outlet)
        @can('delete', $outlet)
            <div class="card">
                <div class="card-header">{{ __('outlet.delete') }}</div>
                <div class="card-body">
                    <label class="control-label text-primary">{{ __('outlet.name') }}</label>
                    <p>{{ $outlet->name }}</p>
                    <label class="control-label text-primary">{{ __('outlet.address') }}</label>
                    <p>{{ $outlet->address }}</p>
                    <label class="control-label text-primary">{{ __('outlet.latitude') }}</label>
                    <p>{{ $outlet->latitude }}</p>
                    <label class="control-label text-primary">{{ __('outlet.longitude') }}</label>
                    <p>{{ $outlet->longitude }}</p>
                    {!! $errors->first('outlet_id', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="card-body text-danger">{{ __('outlet.delete_confirm') }}</div>
                <div class="card-footer">
                    <form method="POST" action="{{ route('outlets.destroy', $outlet) }}" accept-charset="UTF-8" onsubmit="return confirm(&quot;{{ __('app.delete_confirm') }}&quot;)" class="del-form float-right" style="display: inline;">
                        {{ csrf_field() }} {{ method_field('delete') }}
                        <input name="outlet_id" type="hidden" value="{{ $outlet->id }}">
                        <button type="submit" class="btn btn-danger">{{ __('app.delete_confirm_button') }}</button>
                    </form>
                    <a href="{{ route('outlets.edit', $outlet) }}" class="btn btn-link">{{ __('app.cancel') }}</a>
                </div>
            </div>
        @endcan
        @else
        <div class="card">
            <div class="card-header">{{ __('outlet.edit') }}</div>
            <form method="POST" action="{{ route('outlets.update', $outlet) }}" enctype="multipart/form-data" accept-charset="UTF-8">
                {{ csrf_field() }} {{ method_field('patch') }}
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="control-label">{{ __('outlet.name') }}</label>
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $outlet->name) }}" required>
                                {!! $errors->first('name', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                            <div class="form-group">
                                <label for="kode_barang" class="control-label">{{ __('outlet.kode_barang') }}</label>
                                <input id="kode_barang" type="text" class="form-control{{ $errors->has('kode_barang') ? ' is-invalid' : '' }}" name="kode_barang" value="{{ old('kode_barang', $outlet->kode_barang) }}" required>
                                {!! $errors->first('kode_barang', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address" class="control-label">{{ __('outlet.address') }}</label>
                                <textarea id="address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" rows="4">{{ old('address', $outlet->address) }}</textarea>
                                {!! $errors->first('address', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="register" class="control-label">{{ __('outlet.register') }}</label>
                                <input id="register" type="text" class="form-control{{ $errors->has('register') ? ' is-invalid' : '' }}" name="register" value="{{ old('register', $outlet->register) }}" required>
                                {!! $errors->first('register', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                            <div class="form-group">
                                <label for="luas" class="control-label">{{ __('outlet.luas') }}</label>
                                <input id="luas" type="text" class="form-control{{ $errors->has('luas') ? ' is-invalid' : '' }}" name="luas" value="{{ old('luas', $outlet->luas) }}" required>
                                {!! $errors->first('luas', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="polygon" class="control-label">{{ __('outlet.polygon') }}</label>
                                <textarea id="polygon" class="form-control{{ $errors->has('polygon') ? ' is-invalid' : '' }}" name="polygon" rows="4" required>{{ old('polygon', $outlet->polygon) }}</textarea>
                                {!! $errors->first('polygon', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="penggunaan" class="control-label">{{ __('outlet.penggunaan') }}</label>
                                <input id="penggunaan" type="text" class="form-control{{ $errors->has('penggunaan') ? ' is-invalid' : '' }}" name="penggunaan" value="{{ old('penggunaan', $outlet->penggunaan) }}" required>
                                {!! $errors->first('penggunaan', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="harga" class="control-label">{{ __('outlet.harga') }}</label>
                                <input id="harga" type="text" class="form-control{{ $errors->has('harga') ? ' is-invalid' : '' }}" name="harga" value="{{ old('harga', $outlet->harga) }}" required>
                                {!! $errors->first('harga', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="file_path" class="control-label">File Sertifikat</label>
                                <input id="file_path" type="file" class="form-control{{ $errors->has('file_path') ? ' is-invalid' : '' }}" name="file_path" value="{{ old('file_path', $outlet->file_path) }}" placeholder="">
                                {!! $errors->first('file_path', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="file_photo" class="control-label">File Foto</label>
                                <input id="file_photo" type="file" class="form-control{{ $errors->has('file_photo') ? ' is-invalid' : '' }}" name="file_photo" value="{{ old('file_photo', $outlet->file_photo) }}" placeholder="">
                                {!! $errors->first('file_photo', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div id="mapid"></div>
                </div>
                <div class="card-footer">
                    <input type="submit" value="{{ __('outlet.update') }}" class="btn btn-success">
                    <a href="{{ route('outlets.show', $outlet) }}" class="btn btn-link">{{ __('app.cancel') }}</a>
                    @can('delete', $outlet)
                        <a href="{{ route('outlets.edit', [$outlet, 'action' => 'delete']) }}" id="del-outlet-{{ $outlet->id }}" class="btn btn-danger float-right">{{ __('app.delete') }}</a>
                    @endcan
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
    integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
    crossorigin=""/>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

<style>
    #mapid { height: 300px;
            height: 500px
    }
</style>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
    integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
    crossorigin=""></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script>
    var center = [-7.633, 112.900];
    var map = L.map('mapid').setView(center, 15);
    var layer_satelit = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; <a href="https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer">Esri</a>'
    }).addTo(map);
    var layer_jalan = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; <a href="https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer">Esri</a>'
    }).addTo(map);

    var drawnItems = new L.FeatureGroup().addTo(map);
    // var polygonData = {!! $outlet->polygon !!};
    var polygonData = {!! $outlet->polygon ?? '[]' !!};

    // Load existing polygon data
    if (polygonData.length > 0) {
        polygonData.forEach(function(polygon) {
            var poly = L.polygon(polygon, {color: 'blue'}).addTo(drawnItems);
        });
        map.fitBounds(drawnItems.getBounds());
    }

    var drawControl = new L.Control.Draw({
        edit: {
            featureGroup: drawnItems,
            remove: true,
            poly: {
                allowIntersection: false
            }
        },
        draw: {
            polygon: {
                allowIntersection: false,
                showArea: true
            },
            polyline: false,
            rectangle: false,
            circle: false,
            marker: false,
            circlemarker: false
        }
    });

    map.addControl(drawControl);

    map.on('draw:created', function (e) {
        var type = e.layerType,
            layer = e.layer;
        
        drawnItems.addLayer(layer);
        var newPolygonCoordinates = layer.getLatLngs(); 
        updatePolygonTextarea();
    });

    map.on('draw:edited', function (e) {
        updatePolygonTextarea();
    });

    map.on('draw:deleted', function (e) {
        updatePolygonTextarea();
    });

    function updatePolygonTextarea() {
        var polygons = [];
        drawnItems.eachLayer(function(layer) {
            if (layer instanceof L.Polygon) {
                var latLngs = layer.getLatLngs();
                polygons.push(latLngs[0].map(function(latlng) {
                    return { lat: latlng.lat, lng: latlng.lng };
                }));
            }
        });
        document.getElementById('polygon').value = JSON.stringify(polygons);
    }

    updatePolygonTextarea();

</script>
@endpush
