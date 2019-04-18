@extends('layouts.default')

@section('content')
<div class="container">
    <table class="table table-striped table-dark processed-files">
        <tbody>
            @foreach($files as $file)
            <tr>
                <td>{{ $file }}</td>
                <td style="width: 10px"><a href="{{route('view-file', ['name' => $file])}}" target="_blank">Abrir</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection('content')
