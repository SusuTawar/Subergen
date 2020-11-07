@extends('adminlte::page')

@section('title', 'Unduh Dokumen')
@section('plugins.Datatables', true)

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header border-0">
        <h2 class="card-title">Unduh Dokumen</h2>
    </div>
    <form action="{{route("dokumen.make",[$slug])}}" method="post">
        @csrf
        <div class="card-body">
            @foreach ($requireable as $require)
                <div class="form-group">
                    <label for="{{$require["slug"]}}">{{$require["display"]}}</label>
                    <input type="text" class="form-control" name="{{$require["slug"]}}" id="{{$require["slug"]}}" />
                </div>
            @endforeach
        </div>
        <div class="card-footer">
            <input type="submit" class="btn btn-primary" value="Unduh Dokumen" />
        </div>
    </form>
</div>
@stop
