@extends('layouts.app')

@section('title', __('outlet.list'))

@section('content')
<div class="mb-3">
    <div class="float-right">
        @can('create', new App\Outlet)
            <a href="{{ route('outlets.create') }}" class="btn btn-success">{{ __('outlet.create') }}</a>
        @endcan
    </div>
    <h1 class="page-title">{{ __('outlet.list') }} <small>{{ __('app.total') }} : {{ $outlets->total() }} {{ __('outlet.outlet') }}</small></h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <form method="GET" action="" accept-charset="UTF-8" class="form-inline">
                    <div class="form-group">
                        <label for="q" class="control-label">{{ __('outlet.search') }}</label>
                        <input placeholder="{{ __('outlet.search_text') }}" name="q" type="text" id="q" class="form-control mx-sm-2" value="{{ request('q') }}">
                    </div>
                    <input type="submit" value="{{ __('outlet.search') }}" class="btn btn-secondary">
                    <a href="{{ route('outlets.index') }}" class="btn btn-link">{{ __('app.reset') }}</a>
                </form>
            </div>
            <table class="table table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('outlet.name') }}</th>
                        <th>{{ __('outlet.id_pemda') }}</th>
                        <th>{{ __('outlet.address') }}</th>
                        <th>{{ __('outlet.kode_barang') }}</th>
                        <th>{{ __('outlet.luas') }}</th>
                        <th>{{ __('outlet.nomor_sertifikat') }}</th>
                        <th>{{ __('outlet.tanggal_sertifikat') }}</th>
                        <th>{{ __('outlet.hak') }}</th>
                        <th>{{ __('outlet.tahun_pengadaan') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($outlets as $key => $outlet)
                    <tr>
                        <td class="text-center">{{ $outlets->firstItem() + $key }}</td>
                        <td>{!! $outlet->name_link !!}</td>
                        <td>{{ $outlet->id_pemda }}</td>
                        <td>{{ $outlet->address }}</td>
                        <td>{{ $outlet->kode_barang }}</td>
                        <td>{{ $outlet->luas }}</td>
                        <td>{{ $outlet->nomor_sertifikat }}</td>
                        <td>{{ $outlet->tanggal_sertifikat }}</td>
                        <td>{{ $outlet->hak }}</td>
                        <td>{{ $outlet->tahun_pengadaan }}</td>
                        <td class="text-center">
                            <a href="{{ route('outlets.show', $outlet) }}" id="show-outlet-{{ $outlet->id }}">{{ __('app.show') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-body">{{ $outlets->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
</div>
@endsection
