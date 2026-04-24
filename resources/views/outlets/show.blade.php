@extends('layouts.app')

@section('title', __('outlet.detail'))

@section('content')
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ __('outlet.detail') }}</div>
                    <div class="card-body">
                        <table id="outletTable" class="table table-sm">
                            <tbody>
                                <tr>
                                    <td>{{ __('outlet.id_pemda') }}</td>
                                    <td>{{ $outlet->id_pemda }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('outlet.name') }}</td>
                                    <td>{{ $outlet->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('outlet.kode_barang') }}</td>
                                    <td>{{ $outlet->kode_barang }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('outlet.register') }}</td>
                                    <td>{{ $outlet->register }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('outlet.luas') }}</td>
                                    <td>{{ $outlet->luas }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('outlet.tahun_pengadaan') }}</td>
                                    <td>{{ $outlet->tahun_pengadaan }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('outlet.penggunaan') }}</td>
                                    <td>{{ $outlet->penggunaan }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('outlet.harga') }}</td>
                                    <td>{{ $outlet->harga }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('outlet.address') }}</td>
                                    <td>{{ $outlet->address }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('outlet.keterangan') }}</td>
                                    <td>{{ $outlet->keterangan }}</td>
                                </tr>
                                <tr>
                                    <td>Nomer Sertifikat</td>
                                    <td>{{ $outlet->nomor_sertifikat }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Sertifikat</td>
                                    <td>{{ $outlet->tanggal_sertifikat }}</td>
                                </tr>
                                <tr>
                                    <td>Hak</td>
                                    <td>{{ $outlet->hak }}</td>
                                </tr>
                                <tr>
                                    <td>Dokumen Upload</td>
                                    <td>
                                         @if ($outlet->file_path)
                                            <p>PDF File: <a href="{{ Storage::url($outlet->file_path) }}" target="_blank">Download PDF</a></p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Photo</td>
                                    <td>
                                        @if ($outlet->file_photo)
                                            <img src="{{ Storage::url($outlet->file_photo) }}" alt="Photo" style="max-width: 420px;">
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button onclick="window.print()" class="btn btn-primary">Print</button>
                        @can('update', $outlet)
                            <a href="{{ route('outlets.edit', $outlet) }}" id="edit-outlet-{{ $outlet->id }}" class="btn btn-warning">{{ __('outlet.edit') }}</a>
                        @endcan
                        @if(auth()->check())
                            <a href="{{ route('outlets.index') }}" class="btn btn-link">{{ __('outlet.back_to_index') }}</a>
                        @else
                            <a href="{{ route('outlet_map.index') }}" class="btn btn-link">{{ __('outlet.back_to_index') }}</a>
                        @endif
                        
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ trans('outlet.location') }}</div>
                    @if ($outlet->polygon)
                    <div class="card-body">
                        <div id="mapid"></div>
                    </div>
                    @else
                    <div class="card-body">{{ __('outlet.no_coordinate') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
    integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
    crossorigin=""/>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

<style>
    #mapid { height: 400px; }

    @media print {
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .card-footer button,
        .btn, 
        .btn-link {
            display: none;
        }

         #mapid {
            height: 300px; /* Kurangi tinggi peta untuk memastikan tidak terpotong */
            page-break-inside: avoid; /* Hindari pemisahan peta ke halaman berikutnya */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        .card {
            page-break-inside: avoid;
            margin-bottom: 10mm;
        }

        .container {
            width: 100%;
            max-width: 100%;
            padding: 0;
        }

        .row, .col-md-6 {
            display: block;
            width: 100%;
            page-break-inside: avoid;
        }

        .card-header,
        .card-body,
        .card-footer {
            page-break-inside: avoid;
        }

        .card-body {
            margin-bottom: 10mm; /* Kurangi margin bawah untuk menghemat ruang */
        }

        img {
            max-height: 200px; /* Atur batas tinggi maksimal untuk gambar */
            height: auto;
        }

        /* Menyembunyikan elemen yang tidak diperlukan */
        .not-needed-for-print {
            display: none;
        }
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

<script>
    var center = [-7.633, 112.900];
        // Create the map
    var map = L.map('mapid').setView(center, 15);

        // Tambahkan layer satelit
    var layer_satelit = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; <a href="https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer">Esri</a>'
    }).addTo(map);

    // Tambahkan layer nama jalan
    var layer_jalan = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; <a href="https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer">Esri</a>'
    }).addTo(map);

    var polygon = L.polygon({!! $outlet->polygon !!}).addTo(map).bindPopup('{!! $outlet->map_popup_content !!}');
    /*console.log(polygon);*/
    map.fitBounds(polygon.getBounds());
    
   

</script>
@endpush
