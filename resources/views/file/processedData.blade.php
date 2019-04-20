@extends('layouts.default')

@section('content')
<div class="container">
    <table class="table table-striped table-dark processed-files">
        <tbody>

            @if (count($files) < 1)
            <tr>
                <td colspan="2">
                    Não há arquivos processados.
                </td>
            </tr>        
            @endif
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
        <tfoot>
            <td collspan="2" class="table-footer">
                @if (count($files) > 0)
                <a class="btn btn-danger" href="{{route('clean-files')}}"><i class="fa fa-trash"></i> Deletar arquivos processados</a>
                @endif
                <a href="{{url('/')}}">Voltar para o início</a>
            </td>
        </tfoot>
    </table>
</div>
@endsection('content')
