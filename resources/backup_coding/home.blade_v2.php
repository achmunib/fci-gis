@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Data Area</button>
                            <!-- <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Dokumen</button>
                            <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Download</button> -->
                        </div>
                    </nav>
                </div>

                <div class="card-footer">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                            @foreach($outlets as $data)
                            <div class="col-md-8">
                                <div class="card-body" id="mapid"></div>
                            </div>
                            @endforeach
                        </div>
                        <!-- <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">...</div>
                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">...</div> -->
                    </div>
                </div>
            </div>
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
    #mapid { width: 1024px;
  height: 600px;
  display: flex;   
            }
</style>
@endsection
@push('scripts')
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
    integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
    crossorigin="">
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<!-- After Leaflet script -->
<script src="https://unpkg.com/leaflet.featuregroup.subgroup@1.0.2/dist/leaflet.featuregroup.subgroup.js"></script>

<script>

    var layer = new L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });

   var layer_satelit =L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; <a href="http://www.esri.com/">Esri</a>, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',maxZoom: 18,
    });

    var map = L.map('mapid', {layers: [layer, layer_satelit]
            }).setView([{{ config('leaflet.map_center_latitude') }}, {{ config('leaflet.map_center_longitude') }}], {{ config('leaflet.zoom_level') }});
   var baseMaps = {
            "Satelit": layer_satelit,
            "Peta": layer
        };

    var overlayMaps = {};

    @foreach ($outlets as $data)
        // Opsi untuk multi polygon
        var multiPolygonOptions = { color: 'blue', weight: 2 };

        // Membuat layer group untuk setiap outlet
        var multipolygongroup = L.layerGroup();

        // Parsing koordinat polygon dari data
        var polygonCoordinates = {!! json_encode($data->polygon) !!};

            // Membuat poligon dengan koordinat dari data
            var polygon = L.polygon(polygonCoordinates, multiPolygonOptions)
                .bindPopup(
                    "ID PEMDA : {!! $data->id_pemda !!}<br>" +
                    "Koordinat : {!! $data->latitude !!}, {!! $data->longitude !!}<br>" +
                    "Nama Barang : {!! $data->name !!}<br>" +
                    "Alamat : {!! $data->address !!}<br>" +
                    "Luas : {!! $data->luas !!}<br>" +
                    "ID OPD : {!! $data->id_opd !!}<br>" +
                    "OPD : {!! $data->nama_opd !!}<br>" +
                    "SUB OPD : {!! $data->sub_opd !!}<br>" +
                    "UPT : {!! $data->upt !!}<br>"
                );
            // Menambahkan poligon ke grup layer
            polygon.addTo(multipolygongroup);
       
    @endforeach

    // Menambahkan kontrol layer ke peta
    var layerControl = L.control.layers(baseMaps, overlayMaps).addTo(map);
    console.log(layerControl);

</script>
@endpush
