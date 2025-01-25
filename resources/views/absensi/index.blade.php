@extends('adminlte::page')

@section('title', 'Data Absensi')

@section('content_header')
    <h1>Data Absensi</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <a href="{{ route('absensi.create') }}" class="btn btn-primary">Tambah Absensi</a>
            <form action="{{ route('absensi.index') }}" method="GET" class="form-inline ml-auto">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari karyawan..." value="{{ request('search') }}">
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
                            <th>Nama Karyawan</th>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Jumlah Jam Kerja</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($absensis->count() > 0)
                            @foreach($absensis as $index => $absensi)
                                <tr>
                                    <td>{{ $absensis->firstItem() + $index }}</td>
                                    <td>{{ $absensi->karyawan->nama }}</td>
                                    <td>{{ $absensi->tanggal->format('d M Y') }}</td>
                                    <td>{{ $absensi->jam_masuk }}</td>
                                    <td>{{ $absensi->jam_keluar }}</td>
                                    <td>{{ $absensi->jumlah_jam_kerja }} Jam</td>
                                    <td>
                                        <a href="{{ route('absensi.edit', $absensi->id) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('absensi.destroy', $absensi->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">Belum ada yang hadir</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $absensis->appends(request()->input())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@stop
