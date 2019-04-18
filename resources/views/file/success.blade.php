@extends('layouts.default')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="title-upload-success">Arquivo armazenado</h1>
            <a href="{{route('processed.data')}}">
                <span>Verifique dados processados</span>
            </a>
        </div>
    </div>
</div>
@endsection

