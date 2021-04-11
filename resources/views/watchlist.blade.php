@extends('layouts.app')

@push('css')
    <link href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" rel="stylesheet"/>
@endpush

@section('content')
    <div class="container">
        <div class="row mt-3">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <h5 class="card-title">{{ __('Watchlist') }}</h5>
                        <table id="data-table-responsive" class="table table-hover responsive nowrap w-100">
                            <thead>
                            <tr>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>Geen data beschikbaar</tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
