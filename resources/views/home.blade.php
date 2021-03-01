@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <!-- begin row -->
                <div class="row justify-content-center">
                    <!-- begin col-10 -->
                    <div class="col-xl-10">
                        <!-- begin panel -->
                        <div class="panel panel-inverse">
                            <!-- begin panel-body -->
                            <div class="panel-body">
                                <table id="data-table-responsive" class="table table-striped table-bordered table-td-valign-middle">
                                    <thead>
                                        <tr>
                                            <th width="1%"></th>
                                            <th width="1%" data-orderable="false"></th>
                                            <th class="text-nowrap">Rendering engine</th>
                                            <th class="text-nowrap">Browser</th>
                                            <th class="text-nowrap">Platform(s)</th>
                                            <th class="text-nowrap">Engine version</th>
                                            <th class="text-nowrap">CSS grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="odd gradeX">
                                            <td width="1%" class="f-s-600 text-inverse">1</td>
                                            <td width="1%" class="with-img"><img src="/assets/img/user/user-1.jpg" class="img-rounded height-30" /></td>
                                            <td>Trident</td>
                                            <td>Internet Explorer 4.0</td>
                                            <td>Win 95+</td>
                                            <td>4</td>
                                            <td>X</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- end panel-body -->
                        </div>
                        <!-- end panel -->
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