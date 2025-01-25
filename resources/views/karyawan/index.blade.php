@extends('adminlte::page')

@section('title', 'Data Karyawan')

@section('content_header')
    <h1>Data Karyawan</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <a href="{{ route('karyawan.create') }}" class="btn btn-primary">Tambah Karyawan</a>
            <form action="{{ route('karyawan.index') }}" method="GET" class="form-inline ml-auto">
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
                            <th>Nomor Anggota</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No HP</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($karyawans->count() > 0)
                            @foreach($karyawans as $index => $karyawan)
                            
                            @php($encryptedId = Crypt::encryptString($karyawan->nomor_anggota))
                            @php($shareLink = route('absen.form.nomor_anggota', $encryptedId))
                                <tr>
                                    <td>{{ $karyawans->firstItem() + $index }}</td>
                                    <td>{{ $karyawan->nomor_anggota }}</td>
                                    <td>{{ $karyawan->nama }}</td>
                                    <td>{{ $karyawan->alamat }}</td>
                                    <td>{{ $karyawan->no_hp }}</td>
                                    <td>{{ $karyawan->jabatan->nama_jabatan ?? 'N/A' }}</td>
                                    <td>
                                    <a href="{{ route('karyawan.edit', $karyawan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('karyawan.destroy', $karyawan->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Hapus</button>
                                    </form>
                                    <button class="btn btn-success btn-sm" onclick="copyToClipboard('{{ $shareLink }}')">Share Link</button>
                                </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data karyawan</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $karyawans->appends(request()->input())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Link sudah di copy');
            }, function(err) {
                console.error('Error copying text: ', err);
            });
        }
    </script>
@stop
