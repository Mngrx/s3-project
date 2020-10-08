@extends('adminlte::page')

@section('title', 'Buckets')

@section('content_header')
<h1 class="m-0 text-dark">Adicionando foto de gato</strong></h1>
<small>* Fotos que não contenham um gato serão excluídas</small>
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
                <form action="/cat" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nome para a foto com o gato 🐱</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Entre com o nome do objeto">
                    </div>
                    <div class="form-group">
                        <label for="file">Upload da foto do gato 🐱</label>
                        <input type="file" class="form-control" id="file" name="file" placeholder="Faça o upload do arquivo" accept="image/png, image/jpeg">
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar 🐱</button>

                </form>
            </div>
        </div>
    </div>
</div>
@stop
