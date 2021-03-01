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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <!-- begin row -->
                <div class="row justify-content-center">
                    <!-- begin col-10 -->
                    <div class="col-xl-10">
                        
                        <table id="data-table-responsive" class="table table-striped table-bordered table-td-valign-middle">
                            <thead>
                                <tr>
                                    <th width="1%"></th>
                                    <th class="text-nowrap">Rendering engine</th>
                                    <th class="text-nowrap">Browser</th>
                                    <th class="text-nowrap">Platform(s)</th>
                                    <th class="text-nowrap">Engine version</th>
                                    <th class="text-nowrap">CSS grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < 100; $i++) <tr class="odd gradeX">
                                    <td width="1%" class="f-s-600 text-inverse">1</td>
                                    <td>Trident {{$i}}</td>
                                    <td>Internet Explorer 4.0</td>
                                    <td>Win 95+</td>
                                    <td>4</td>
                                    <td>X</td>
                                    </tr>
                                    @endfor
                            </tbody>
                        </table>
                    </div>
                    <!-- end col-10 -->
                </div>
                <!-- end row -->

                <!-- <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div> -->
            </div>
        </div>
    </div>
</div>
@endsection