@extends('layouts.app')

@section('title', __('outlet.list'))

@section('content')
<div class="mb-3">
    <div class="float-right">
        <a href="{{ route('opd_create') }}" class="btn btn-success">Create NEW OPD</a>
    </div>
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
                        <th>No</th>
                        <th>Nama OPD</th>
                        <th>Sub OPD</th>
                        <th>UPT</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opd as $key => $opd)
                    <tr>
                        <td>{{ $opd->id_opd }}</td>
                        <td>{{ $opd->nama_opd }}</td>
                        <td>{{ $opd->sub_opd }}</td>
                        <td>{{ $opd->upt }}</td>
                        <td class="text-center">
                            
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        </div>
    </div>
</div>

@endsection
