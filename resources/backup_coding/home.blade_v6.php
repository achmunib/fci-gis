<!DOCTYPE html>
<html>
<head>
    <title>Peta dengan Pencarian</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-search@2.9.9/dist/leaflet-search.min.css" />
    <style>
        #mapid { height: 600px; }
    </style>
</head>
<body>
    <div id="mapid"></div>
    <input type="checkbox" id="select-all"> Select All

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-search@2.9.9/dist/leaflet-search.min.js"></script>
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
        var searchLayer = L.layerGroup(); // Layer untuk pencarian

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
                    var polygon = L.polygon([{!! $data->polygon ?? '[]' !!}], multiPolygonOptions)
                        .bindPopup(item.content)  // Gunakan item.content di sini
                        .addTo(item.layer);

                    // Tambahkan properties ke polygon untuk pencarian
                    polygon.feature = { properties: { name: outletName } };

                    // Tambahkan polygon ke searchLayer untuk pencarian
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

        // Tambahkan kontrol pencarian ke peta
        var searchControl = new L.Control.Search({
            layer: searchLayer,
            propertyName: 'name', // Tentukan properti untuk pencarian
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

    </script>
</body>
</html>
