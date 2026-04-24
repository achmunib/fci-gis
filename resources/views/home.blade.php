@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Dashboard Area</button>
                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Dashboard Data</button>
                            <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Download</button>
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
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <h1>Data Media</h1>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card border-primary mb-3" style="max-width: 18rem;">
                                            <div class="card-header">Total Data</div>
                                            <div class="card-body text-primary">
                                                <div class="d-flex align-items-center">
                                                    <span class="material-icons">inventory</span>
                                                    <h5 class="card-title ms-2">Jumlah Data Yang Ter Upload</h5>
                                                </div>
                                                <p class="card-text">Sejumlah {{ $totalData }} bidang tanah</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card border-primary mb-3" style="max-width: 18rem;">
                                            <div class="card-header">Data OPD</div>
                                            <div class="card-body text-primary">
                                                <div class="d-flex align-items-center">
                                                    <span class="material-icons">assured_workload</span>
                                                    <h5 class="card-title ms-2">OPD</h5>
                                                </div>
                                                <p class="card-text">Total OPD yang memiliki Nama Barang Tanah "Tanah Usaha"</p>
                                                <p class="card-text">Sejumlah {{ $totalOPD }} OPD</p>
                                                <p class="card-text">Total Luas  {{ $totalLuas }} m²</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card border-primary mb-3" style="max-width: 18rem;">
                                            <div class="card-header">Data Tanah</div>
                                            <div class="card-body text-primary">
                                                <div class="d-flex align-items-center">
                                                    <span class="material-icons">analytics</span>
                                                    <h5 class="card-title ms-2">BADAN PENGELOLAAN KEUANGAN DAN ASET</h5>
                                                </div>
                                                <p class="card-text">Total yang dimiliki untuk Nama Barang Tanah "Tanah Usaha"</p>
                                                <p class="card-text">Sejumlah {{ $totalbpka->jumlah_opd }} bidang tanah</p>
                                                <p class="card-text">Total Luas  {{ $totalbpkaluas }} m²</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <h1>Grafik Tanah Usaha per OPD</h1>
                                    <canvas id="myChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                            <h5>Data as soon to Display, Submit your request to your trusted expert......</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('styles')
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
    integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
    crossorigin=""/>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet-search@2.9.9/dist/leaflet-search.min.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


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
<script src="https://unpkg.com/leaflet-search@2.9.9/dist/leaflet-search.min.js"></script>
<script src="https://unpkg.com/leaflet.featuregroup.subgroup@1.0.2/dist/leaflet.featuregroup.subgroup.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Base Layers
    var layer_satelit = new L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });

    var layer = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; <a href="http://www.esri.com/">Esri</a>, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
        maxZoom: 18,
    });

    var map = L.map('mapid', {
        layers: [layer_satelit, layer]
    }).setView([{{ config('leaflet.map_center_latitude') }}, {{ config('leaflet.map_center_longitude') }}], {{ config('leaflet.zoom_level') }});

    var baseMaps = {
        "Peta": layer_satelit,
        "Satelit": layer
    };

    var overlayMapsOPD = {};
    var overlayMapsName = {};
    var searchLayer = L.layerGroup();

        @foreach ($outlets as $data)
            var opdName = '{!! $data->nama_opd !!}';
            var outletName = '{!! $data->name !!}';
            var color = '{!! $data->color !!}';

            if (!overlayMapsOPD[opdName]) {
                overlayMapsOPD[opdName] = L.layerGroup();
            }

            if (!overlayMapsName[outletName]) {
                overlayMapsName[outletName] = L.layerGroup();
            }

            @if (!empty($data->polygon))
                var multiPolygonOptions = { color: color, weight: 1 };
                var polygonData = [
                    {
                        layer: overlayMapsOPD[opdName], 
                        content: 'Data : {{ $data->id }}<br>' +
                                 '<img src="{{ Storage::url($data->file_photo) }}" alt="Photo" style="max-width: 200px;"><br>' +
                                 'ID PEMDA : {{ $data->id_pemda }}<br>' +
                                 'Nama Barang : {{ $data->name }}<br>' +
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
                                 'Luas : {{ $data->luas }}<br>' +
                                 'OPD : {{ $data->nama_opd }}<br>' +
                                 'SUB OPD : {{ $data->sub_opd }}<br>' +
                                 'UPT : {{ $data->upt }}<br>' +
                                 '<a href="{{ route('outlets.show', ['outlet' => $data->id]) }}">Detail Outlet</a>'
                    }
                ];

                polygonData.forEach(function(item) {
                    var polygon = L.polygon([{!! $data->polygon ?? '[]' !!}], multiPolygonOptions)
                        .bindPopup(polygonData[0].content)
                        .addTo(item.layer);

                    polygon.feature = { properties: { name: outletName } };
                    searchLayer.addLayer(polygon);
                });
            @endif

        @endforeach

    // Function to handle checkbox "Select All"
    document.getElementById("select-all").addEventListener("change", function(e) {
        var isChecked = e.target.checked;
        // Toggle visibility of all overlay layers
        Object.keys(overlayMapsOPD).forEach(function(opdName) {
            if (isChecked) {
                map.addLayer(overlayMapsOPD[opdName]);
            } else {
                map.removeLayer(overlayMapsOPD[opdName]);
            }
        });
        Object.keys(overlayMapsName).forEach(function(outletName) {
            if (isChecked) {
                map.addLayer(overlayMapsName[outletName]);
            } else {
                map.removeLayer(overlayMapsName[outletName]);
            }
        });
    });

    // Inisialisasi kontrol layer untuk dropdown OPD dan Name
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

    var searchControl = new L.Control.Search({
            layer: searchLayer,
            propertyName: 'name',
            initial: false,
            zoom: 12,
            marker: false,
            textPlaceholder: 'Cari...'
        });

    map.addControl(searchControl);

    // Event listener untuk menghapus poligon selain yang dicari
        searchControl.on('search:locationfound', function(e) {
            // Dapatkan semua layer dengan properti yang cocok
            var matchedLayers = [];
            searchLayer.eachLayer(function(layer) {
                if (layer.feature && layer.feature.properties && layer.feature.properties.name === e.text) {
                    matchedLayers.push(layer);
                }
            });

            // Hapus semua layer dari searchLayer
            searchLayer.clearLayers();

            // Tambahkan semua layer yang ditemukan ke searchLayer
            matchedLayers.forEach(function(layer) {
                searchLayer.addLayer(layer);
            });

            // Fokus ke batas dari semua layer yang ditemukan
            var bounds = L.latLngBounds([]);
            matchedLayers.forEach(function(layer) {
                if (layer.getBounds) {
                    bounds.extend(layer.getBounds());
                }
            });
            if (bounds.isValid()) {
                map.fitBounds(bounds);
            }
        });

    var ctx = document.getElementById('myChart').getContext('2d');
    var labels = {!! json_encode($grafiktotal->pluck('nama_opd')) !!};
    var dataJumlah = {!! json_encode($grafiktotal->pluck('jumlah')) !!};
    
    var myChart = new Chart(ctx, {
        type: 'bar', // Jenis grafik bisa 'line', 'bar', 'pie', dll.
        data: {
            labels: labels, // Label untuk sumbu X
            datasets: [{
                label: 'Jumlah OPD Tanah Usaha',
                data: dataJumlah, // Data untuk sumbu Y
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

</script>
@endpush
