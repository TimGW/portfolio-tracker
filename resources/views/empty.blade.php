@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
        <h1>Welkom</h1>
        <p class="text-center">Je hebt nog geen (XLS / CSV) <strong>transacties</strong> vanuit 'de Giro' geimporteerd. </br> Klik op de knop hieronder om te beginnen</p>
        <form id="import-data" action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
            <label class="btn btn-primary mb-5" >
                Importeer transacties<input type="file" name="file" class="form-control" onchange="this.form.submit()" hidden>
            </label>
            @csrf
        </form>
    </div>
@endsection
