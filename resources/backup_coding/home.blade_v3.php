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
                            <input type="checkbox" id="select-all"> <label for="select-all">Select All</label>
                            <!-- Contoh dropdown untuk memilih layer berdasarkan OPD atau Name -->
                            <select onchange="changeOverlayMap(this.value)">
                                <option value="OPD">Berdasarkan OPD</option>
                                <option value="Name">Berdasarkan Nama</option>
                            </select>
                            <div class="card-body" id="mapid"></div>
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
    #mapid { width: 1240px;
  height: 700px;
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
	var overlayMapsOPD = {};
        var overlayMapsName = {};
        var allPolygonLayers = [];

        @foreach ($outlets as $data)
            var opdName = '{!! $data->nama_opd !!}';
            var outletName = '{!! $data->name !!}';

            if (!overlayMapsOPD[opdName]) {
                overlayMapsOPD[opdName] = L.layerGroup();
            }

            if (!overlayMapsName[outletName]) {
                overlayMapsName[outletName] = L.layerGroup();
            }

            @if (!empty($data->polygon))
                var multiPolygonOptions = { color: 'yellow', weight: 1 };
                var polygonData = [
                    {
                        layer: overlayMapsOPD[opdName], 
                        content: 'Data : {{ $data->id }}<br>' +
                                 '<img src="{{ Storage::url($data->file_photo) }}" alt="Photo" style="max-width: 200px;"><br>' +
                                 'ID PEMDA : {{ $data->id_pemda }}<br>' +
                                 'Nama Barang : {{ $data->name }}<br>' +
                                 'Alamat : {{ $data->address }}<br>' +
                                 'Luas : {{ $data->luas }}<br>' +
                                 'OPD : {{ $data->nama_opd }}<br>' +
                                 'SUB OPD : {{ $data->sub_opd }}<br>' +
                                 'UPT : {{ $data->upt }}<br>' +
                                 '<a href="{{ route('outlets.show', ['outlet' => $data->id]) }}">Detail Outlet</a>'
                    },
                    {
                        layer: overlayMapsName[outletName],
                        content: 'Data : {{ $data->id }}<br>' +
                                 '<img src="{{ Storage::url($data->file_photo) }}" alt="Photo" style="max-width: 200px;"><br>' +
                                 'ID PEMDA : {{ $data->id_pemda }}<br>' +
                                 'Nama Barang : {{ $data->name }}<br>' +
                                 'Alamat : {{ $data->address }}<br>' +
                                 'Luas : {{ $data->luas }}<br>' +
                                 'OPD : {{ $data->nama_opd }}<br>' +
                                 'SUB OPD : {{ $data->sub_opd }}<br>' +
                                 'UPT : {{ $data->upt }}<br>' +
                                 '<a href="{{ route('outlets.show', ['outlet' => $data->id]) }}">Detail Outlet</a>'
                    }
                ];

                polygonData.forEach(function(item) {
                    var polygonLayer = L.polygon([{!! $data->polygon !!}], multiPolygonOptions)
                        .bindPopup(item.content);

                    polygonLayer.addTo(item.layer);
                    allPolygonLayers.push(polygonLayer);
                });
            @endif

        @endforeach

        // Fungsi untuk toggle semua polygon layers
        function toggleAllPolygons() {
            if (document.getElementById('selectAll').checked) {
                allPolygonLayers.forEach(function(layer) {
                    map.addLayer(layer);
                });
            } else {
                allPolygonLayers.forEach(function(layer) {
                    map.removeLayer(layer);
                });
            }
        }

        // Inisialisasi kontrol layer untuk dropdown OPD dan Name
        var baseMaps = {}; // Tentukan baseMaps sesuai kebutuhan Anda
        var map = L.map('map').setView([0, 0], 2); // Tentukan koordinat awal dan zoom level peta sesuai kebutuhan

        var controlLayersOPD = L.control.layers(baseMaps, overlayMapsOPD);
        var controlLayersName = L.control.layers(baseMaps, overlayMapsName);

        // Tambahkan kontrol layer OPD ke peta secara default
        controlLayersOPD.addTo(map);

        // Event listener untuk mengubah kontrol layer berdasarkan dropdown yang dipilih
        function changeOverlayMap(selector) {
            if (selector === 'OPD') {
                map.removeControl(controlLayersName);
                controlLayersOPD.addTo(map);
            } else if (selector === 'Name') {
                map.removeControl(controlLayersOPD);
                controlLayersName.addTo(map);
            }
        }

        // Contoh penggunaan event listener untuk mengubah dropdown
        changeOverlayMap('OPD'); // Menampilkan layer OPD secara default
</script>
@endpush
