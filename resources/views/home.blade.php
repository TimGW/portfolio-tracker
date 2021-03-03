@extends('layouts.app')

@push('css')
	<link href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
@endpush

@section('content')

<div class="container">
    <div class="row">
        <div class="col-lg-6 mt-3">
            <div class="card">
                <div class="card-header">{{ __('Sectorverdeling') }}</div>

                <div class="card-body">
                    <canvas id="doughnut-chart" data-render="chart-js"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mt-3">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">

                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">{{ __('Aandelen') }}</div>

                <div class="card-body">
                    <table id="data-table-responsive" class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Symbool / ISIN</th>
                                <th>Aantal</th>
                                <th>Slotkoers</th>
                                <th>Lokale waarde</th>
                                <th>Waarde in EUR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stocks as $stock)
                                <tr>
                                    <td>{{$stock->product}}</td>
                                    <td>{{$stock->symbol_isin}}</td>
                                    <td>{{$stock->quantity}}</td>
                                    <td>{{$stock->closing_price}}</td>
                                    <td>{{$stock->local_value}}</td>
                                    <td>{{$stock->value_in_euros}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="/js/portfolio-chart.js"></script>
<script src="/js/portfolio-datatable.js"></script>
@endpush