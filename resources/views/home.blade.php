@extends('layouts.app')

@section('content')

<script>
    $(document).ready(function() {
        $('#data-table-responsive').DataTable({
            responsive: true
        });
    });
</script>

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
                    <table id="data-table-responsive" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">First</th>
                                <th scope="col">Last</th>
                                <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 100; $i++) <tr>
                                <th scope="row">{{$i}}</th>
                                <td>Mark</td>
                                <td>Otto</td>
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
@endpush