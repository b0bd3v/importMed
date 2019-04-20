@extends('layouts.default')

@section('content')
<div class="container">
    <table class="table table-striped table-dark processed-files">
        <tbody>
            @foreach($files as $file)
            <tr>
                <td>
                    <span>{{ $file }}</span>
                </td>
                <td style="width: 116px">
                    <a class="btn btn-primary" href="{{route('view-file', ['name' => $file])}}" target="_blank"><i class="fa fa-download"></i> Baixar</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection('content')
