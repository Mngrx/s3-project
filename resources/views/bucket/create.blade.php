@extends('adminlte::page')

@section('title', 'Buckets')

@section('content_header')
<h1 class="m-0 text-dark">Criando bucket</h1>
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
                <form action="/buckets" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="bucket">Nome do bucket</label>
                        <input type="text" class="form-control" id="bucket" name="bucket" placeholder="Entre com o nome do bucket">
                    </div>
                    <div class="form-group">
                        <label for="description">Descrição do bucket</label>
                        <textarea class="form-control" id="description" name="description" placeholder="Entre com descrição do bucket"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar bucket</button>

                </form>
            </div>
        </div>
    </div>
</div>
@stop
