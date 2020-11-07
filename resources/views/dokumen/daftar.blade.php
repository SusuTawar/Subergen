@extends('adminlte::page')

@section('title', 'Kelola Dokumen')
@section('plugins.Datatables', true)

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header border-0">
        <h2 class="card-title">Kelola Dokumen</h2>
        <div class="card-tools">
            <a href="#" class="btn btn-tool btn-sm">
                <i class="fas fa-download"></i>
            </a>
            <a href="#" class="btn btn-tool btn-sm">
                <i class="fas fa-bars"></i>
            </a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table id="dokumenTable" class="table table-striped table-valign-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th class="w-25">More</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $dokumen)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $dokumen->title }}</td>
                        <td>{{ $dokumen->category }}</td>
                        <td class="btn-group d-flex space-between">
                            <a href="{{ route('dokumen.download',[$dokumen->slug]) }}" class="btn btn-primary">Unduh</a>
                            <a href="{{ route('dokumen.delete',[$dokumen->slug]) }}" class="btn btn-danger">Hapus</a>
                        </td>
                    <tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>
@stop
