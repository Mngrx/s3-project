@extends('adminlte::page')

@section('title', 'Buckets')

@section('content_header')
<h1 class="m-0 text-dark">Adicionando objeto para o bucket <strong>{{ $bucket->name }}</strong></h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </div>
                @endif
                <form action="/buckets/{{ $bucket->id }}/objects" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nome para o objetos</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Entre com o nome do objeto">
                    </div>
                    <div class="form-group">
                        <label for="file">Upload do objeto</label>
                        <input type="file" class="form-control" id="file" name="file" placeholder="Faça o upload do arquivo">
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar objeto</button>

                </form>
            </div>
        </div>
    </div>
</div>
@stop
