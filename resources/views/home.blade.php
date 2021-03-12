@extends('layouts.app')

@push('css')
    <link href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" rel="stylesheet"/>
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
                                <h3 class="card-title">€{{ $portfolio->total_current_value }}</h3>

                            </div>
                        </div>
                    </div>
                    <div class="col mt-3">
                        <div class="card h-100 text-center">
                            <div class="card-header">{{ __('Totaal winst/verlies') }}</div>

                            <div class="card-body">
                                <h3 class="card-title">€{{ $portfolio->total_profit }}</h3>

                            </div>
                        </div>
                    </div>
                    <div class="col mt-3">
                        <div class="card h-100 text-center">
                            <div class="card-header">{{ __('Groei') }}</div>

                            <div class="card-body">
                                <h3 class="card-title">{{ $portfolio->total_growth }}%</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col mt-3">
                        <div class="card h-100 text-center">
                            <div class="card-header">{{ __('Totaal geinvesteerd') }}</div>

                            <div class="card-body">
                                <h3 class="card-title">€{{ $portfolio->total_invested }}</h3>

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
                                <th>Aantal</th>
                                <th>GAK</th>
                                <th>Koers</th>
                                <th>Winst / Verlies</th>
                                <th>%</th>
                                <th>Waarde</th>
                                <th>Gewicht</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($portfolio->stock_list as $stock)
                                <tr>
                                    <td>{{$stock->stock_name}}</td>
                                    <td>{{$stock->volume_of_shares}}</td>
                                    <td>€{{$stock->ps_avg_price_purchased}}</td>
                                    <td>€{{$stock->ps_current_value}}</td>
                                    <td>€{{$stock->ps_profit}}</td>
                                    <td>{{$stock->ps_profit_percentage}}%</td>
                                    <td>€{{$stock->stock_current_value}}</td>
                                    <td data-order="{{$stock->stock_weight}}"><progress value="{{$stock->stock_weight}}" max="100"></progress></td>
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
