@extends('layouts.default')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session()->get('message'))
            <div class="alert alert-success">
              {{ session()->get('message') }}
            </div>
            @endif
            <div class="card">
                <div class="card-header">Processar arquivo</div>
                <div class="card-body">
                {{ Form::open(array('url' => route('process'),'files'=>'true')) }}
                      @csrf
                      <div class="form-group row ">
                        <label for="title" class="col-sm-4 col-form-label text-md-right">Arquivo</label>
                        <div class="col-md-6">
                            <input type="file" name="file" accept=".dat">
                        </div>
                      </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Processar
                                </button>
                            </div>
                        </div>
                {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
