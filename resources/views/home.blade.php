@extends('layouts.app')

@push('css')
<link href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-6 mt-3">
            <div class="card h-100">
                <div class="card-header">{{ __('Sectorverdeling') }}</div>

                <div class="card-body">
                    <canvas id="doughnut-chart" data-render="chart-js"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <div class="col mt-3">
                    <div class="card h-100 text-center">
                        <div class="card-header">{{ __('Balans') }}</div>

                        <div class="card-body">
                            <h3 class="card-title">€ 10000</h3>

                        </div>
                    </div>
                </div>
                <div class="col mt-3">
                    <div class="card h-100 text-center">
                        <div class="card-header">{{ __('Groei') }}</div>

                        <div class="card-body">
                            <h3 class="card-title">- 2,5%</h3>

                        </div>
                    </div>
                </div>
                <div class="col mt-3">
                    <div class="card h-100 text-center">
                        <div class="card-header">{{ __('Gemiddeld dividend') }}</div>

                        <div class="card-body">
                            <h3 class="card-title">+ 3,07%</h3>
                        </div>
                    </div>
                </div>
                <div class="col mt-3">
                    <div class="card h-100 text-center">
                        <div class="card-header">{{ __('Jaarlijks inkomen') }}</div>

                        <div class="card-body">
                            <h3 class="card-title">€ 500</h3>

                        </div>
                    </div>
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
                                <th>Bedrijf</th>
                                <th>Huidige waarde</th>
                                <th>Koers</th>
                                <th>Aantal shares</th>
                                <th>Winst / Verlies</th>
                                <th>Gewicht</th>
                            </tr>
                        </thead>
                        <tbody>

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

<script>
    $(document).ready(
        setData(
            {!! json_encode($chart->labels, JSON_HEX_TAG) !!}, 
            {!! json_encode($chart->dataset, JSON_HEX_TAG) !!},
            {!! json_encode($chart->colours, JSON_HEX_TAG)!!}
        )
    )
</script>

@endpush