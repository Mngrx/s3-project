@extends('adminlte::page')

@section('title', 'Objects')

@section('content_header')
<h1 class="m-0 text-dark">Aqui só terá fotos com gato</h1>
<small>Possa ser que veja fotos que não sejam de gato aqui, mas logo mais elas estarão eliminadas</small>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div style="float: right;">
                    <a href="/cat/create" class="btn btn-primary">Adicionar novo objeto</a>
                </div>
                <br><br><br>
                @if($objects)
                <div class="table-responsive-sm">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                {{-- <th scope="col">#</th> --}}
                                <th scope="col">Nome</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($objects as $object)
                            <tr>
                                <th scope="row">{{ $object['Key'] }}</th>
                                {{-- <td>{{ $object['Key'] }}</td> --}}
                                <td><a href="/cat/{{ $object['Key'] }}" class="btn btn-success"><i class="fas fa-download"></i></a> <a href="/cat/{{ $object['Key'] }}/delete" onclick="return confirm('Você tem certeza que quer deletar este objeto?')" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop
