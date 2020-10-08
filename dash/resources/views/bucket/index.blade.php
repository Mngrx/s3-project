@extends('adminlte::page')

@section('title', 'Buckets')

@section('content_header')
<h1 class="m-0 text-dark">Buckets</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div style="float: right;">
                    <a href="/buckets/create" class="btn btn-primary">Criar novo bucket</a>
                </div>
                <br><br><br>
                @if($buckets)
                <div class="table-responsive-sm">

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buckets as $bucket)
                        <tr>
                            <th scope="row">{{ $bucket->id }}</th>
                            <td>{{ $bucket->name }}</td>
                            <td>{{ substr($bucket->description, 0, 100) }}</td>
                            <td><a href="/buckets/{{ $bucket->id }}/objects" class="btn btn-success"><i class="fas fa-list"></i></a>    <a  href="/buckets/{{ $bucket->id }}/delete" onclick="return confirm('Você tem certeza que quer deletar este Bucket?')" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a></td>
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
@stop
