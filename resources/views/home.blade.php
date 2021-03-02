@extends('layouts.app')

@push('css')
	<link href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
@endpush

@section('content')

<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">{{ __('Sectorverdeling') }}</div>

                <div class="card-body">
                    <canvas id="doughnut-chart" data-render="chart-js"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
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
                <div class="card-header">{{ __('Tabel') }}</div>

                <div class="card-body">
                    <table id="data-table-responsive" class="table">
                        <thead>
                            <tr>
                                <th>Bedrijf</th>
                                <th>Huidige waarde</th>
                                <th>Koers</th>
                                <th>Aantal</th>
                                <th>Winst / Verlies</th>
                                <th></th>
                                <th>Gewicht</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 100; $i++) 
                                <tr>
                                    <td>{{$i}} Apple</td>
                                    <td>€ 2000</td>
                                    <td>€ 1000</td>
                                    <td>@mdo</td>
                                    <td>@mdo</td>
                                    <td>@mdo</td>
                                    <td>@mdo</td>
                                </tr>
                            @endfor
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