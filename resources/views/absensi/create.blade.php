@extends('adminlte::page')

@section('title', 'Tambah Absensi')

@section('content_header')
    <h1>Tambah Absensi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
        <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>No. Anggota</th>
                            <th>Nama Karyawan</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($id=1)
                        @if($karyawan->count()>0)
                        @foreach($karyawan as $data)
                            <?php
                                $absensi = DB::table('absensis')
                                            ->where('tanggal','=',$today)
                                            ->where('karyawan_id','=',$data->id)
                                            ->where('deleted_at','=',null)
                                            ->first();
                            ?>
                            <tr>
                                <td>{{ $id }}</td>
                                <td>{{ $data->nomor_anggota }}</td>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->jabatan->nama_jabatan }}</td>
                                <td>
                                    @if(empty($absensi))
                                    <form action="{{ route('absensi.masuk', $data->id) }}" method="POST" style="display:inline-block;">
                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-sm btn-success">Masuk Kerja</button>
                                    </form>
                                    @elseif($absensi->jam_keluar==null)
                                    <form action="{{ route('absensi.keluar', $data->id) }}" method="POST" style="display:inline-block;">
                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-sm btn-danger">Pulang Kerja</button>
                                    </form>
                                    @else
                                    <span>Sudah Absen Hari Ini</span>
                                    @endif
                                </td>
                            </tr>
                            @php($id++)
                        @endforeach
                        @else
                            <tr>
                                <td colspan="5">Tidak ada karyawan</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
