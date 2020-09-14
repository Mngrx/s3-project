@extends('adminlte::page')

@section('title', 'Objects')

@section('content_header')
<h1 class="m-0 text-dark">Bucket: {{ $bucket->name }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div style="float: right;">
                    <a href="/buckets/{{$bucket->id}}/objects/create" class="btn btn-primary">Adicionar novo objeto</a>
                </div>
                <br><br><br>
                @if($objects)
                <div class="table-responsive-sm">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($objects as $object)
                            <tr>
                                <th scope="row">{{ $object->id }}</th>
                                <td>{{ $object->name }}</td>
                                <td><a href="/buckets/{{ $bucket->id }}/objects/{{ $object->id }}" class="btn btn-success"><i class="fas fa-download"></i></a> <a href="/buckets/{{ $bucket->id }}/objects/{{ $object->id }}/delete" onclick="return confirm('Você tem certeza que quer deletar este objeto?')" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a></td>
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
