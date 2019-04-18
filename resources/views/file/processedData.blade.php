@extends('layouts.default')

@section('content')
<div class="container">
    <table class="table table-striped table-dark processed-files">
        <tbody>
            @foreach($files as $file)
            <tr>
                <td>{{ $file }}</td>
                <td><a href="{{route('processed.data', ['name' => $file])}}" target="_blank">Abrir</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection('content')