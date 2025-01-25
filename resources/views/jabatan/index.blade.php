@extends('adminlte::page')

@section('title', 'Data Jabatan')

@section('content_header')
    <h1>Data Jabatan</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <a href="{{ route('jabatan.create') }}" class="btn btn-primary">Tambah Jabatan</a>
            <form action="{{ route('jabatan.index') }}" method="GET" class="form-inline ml-auto">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari jabatan..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Jabatan</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($jabatans->count() > 0)
                            @foreach($jabatans as $index => $jabatan)
                                <tr>
                                    <td>{{ $jabatans->firstItem() + $index }}</td>
                                    <td>{{ $jabatan->nama_jabatan }}</td>
                                    <td>{{ $jabatan->keterangan }}</td>
                                    <td>
                                        <a href="{{ route('jabatan.edit', $jabatan->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('jabatan.destroy', $jabatan->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data jabatan</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $jabatans->appends(request()->input())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@stop
