@extends('layouts.app')

@section('content')
<body class="background-image">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <!-- <div class="card p-4 bg-dark bg-opacity-50">
            <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-9 text-center text-light">
                        <h2>SILAT</h2>
                        <h3>Sistem Informasi LAhan Tanah</h3>
                        <h5>Web Geographic Information System</h5>
                        <h5>The System Creates, Manages, Connects Data to a Map, Integrating Location</h5>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</body>

@endsection

@section('styles')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
    integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
    crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

<style>
  body {
            font-family: 'Roboto', sans-serif; /* Mengatur font default untuk seluruh body */
        }
  .background-image {
      background-image: url('{{ asset('img/bg_4.png') }}');
      background-size: 1240px 900px;
      background-repeat: no-repeat;
      background-position: center; 
      color: #ffffff; /* Light text for contrast */
  }
</style>
@endsection
@push('scripts')
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
    integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
    crossorigin=""></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>


@endpush
