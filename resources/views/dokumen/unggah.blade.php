@extends('adminlte::page')

@section('title', 'Unggah Template Dokumen')
@section('plugins.Datatables', true)

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header border-0">
        <h2 class="card-title">Unggah Template Dokumen</h2>
    </div>
    <form method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="name">Nama Template Dokumen:</label>
                <input type="text" class="form-control" id="name" name="name"  />
            </div>
            <div class="form-group">
                <label for="category">Kategori Dokumen:</label>
                <input type="text" class="form-control" id="category" name="category"  />
            </div>
            <div class="form-group">
                <label for="document">Berkas Dokumen (docx):</label>
                <input type="file" class="form-control" id="document" name="document"  />
            </div>
        </div>
        <div class="card-footer">
            <input type="submit" class="btn btn-primary" value="Unggah" />
        </div>
    </form>
</div>
@stop

@section('js')
<script>
    @foreach ($errors->all() as $error)
                console.log("{{ $error }}");
            @endforeach
</script>
@stop
