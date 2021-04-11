@extends('layouts.app')

@push('css')
    <link href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" rel="stylesheet"/>
@endpush

@section('content')
{{--    <div class="container">--}}
{{--        <div class="row justify-content-end mx-auto">--}}
{{--            <h6>--}}
{{--                <div class="bg-secondary text-light rounded-bottom p-2">--}}
{{--                    Laatst geüpdatet: {{ \Carbon\Carbon::parse($portfolio->updated_at)->diffForhumans() }}--}}
{{--                </div>--}}
{{--            </h6>--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="container">
        <div class="row row-cols-xl-4 row-cols-lg-4 row-cols-2">
            <div class="col mt-3">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <p class="card-text">{{ __('Balans') }}</p>
                        <h3 class="card-title">€{{ number_format($portfolio->total_current_value, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col mt-3">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <p class="card-text">{{ __('Winst (ong.)') }}</p>
                        <h3 class="card-title">€{{ number_format($portfolio->total_profit, 0, ',', '.') }}</h3>

                    </div>
                </div>
            </div>
            <div class="col mt-3">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <p class="card-text">{{ __('Groei') }}</p>
                        <h3 class="card-title">{{ number_format($portfolio->total_growth, 2, ',', '.') }}%</h3>
                    </div>
                </div>
            </div>
            <div class="col mt-3">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <p class="card-text">{{ __('Geïnvesteerd') }}</p>
                        <h3 class="card-title">€{{ number_format($portfolio->total_invested, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Sectorverdeling op waarde') }}</h5>
                        <div class="col-xl-11">
                            <div class="chart-container" style="height:30vh;">
                                <canvas id="doughnut-chart" data-render="chart-js"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <h5 class="card-title">{{ __('Aandelen') }}</h5>
                        <table id="data-table-responsive" class="table table-hover responsive nowrap w-100">
                            <thead>
                            <tr>
                                <th class="no-sort"></th>
                                <th style="width: 30%">Bedrijf</th>
                                <th>Koers</th>
                                <th>Waarde</th>
                                <th>Winst (ong.)</th>
                                <th>Groei</th>
                                <th>Gewicht</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($portfolio->stocks as $stock)
                                <tr data-toggle="modal" data-id="{{$stock->id}}"
                                    data-target="#modal-responsive{{$stock->id}}">
                                    <td>
                                        <div class="card" style="width: 1.5rem;">
                                            <img class="card-img mx-auto d-block"
                                                 src="{{$stock->firstProfile()->image}}">
                                        </div>
                                    </td>
                                    <td>{{$stock->firstProfile()->companyName}}</td>
                                    <td>€{{ number_format($stock->firstProfile()->price, 2, ',', '.') }}</td>
                                    <td>€{{ number_format($stock->stock_current_value, 2, ',', '.') }}</td>
                                    <td>€{{ number_format($stock->ps_profit, 2, ',', '.') }}</td>
                                    <td>{{ number_format($stock->ps_profit_percentage, 2, ',', '.') }}%</td>
                                    <td data-order="{{$stock['stock_weight']}}">
                                        <progress value="{{$stock['stock_weight']}}" max="100"></progress>
                                        &nbsp;{{ number_format($stock->stock_weight, 2, ',', '.') }}%
                                    </td>
                                </tr>
                            @empty
                                <tr>Geen data beschikbaar</tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @each('includes.modal', $portfolio->stocks, 'stock', 'empty')

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
