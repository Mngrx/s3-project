@extends('adminlte::page')

@section('title', 'Página Inicial')

@section('content_header')
    <h1 class="m-0 text-dark">Página Inicial</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">Bem-vindo, {{ Auth::user()->name }}! Agora você poderá mexer no S3.</p>
                </div>
            </div>
        </div>
    </div>
@stop
