@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <!-- Card Jumlah Karyawan -->
        <div class="col-lg-6 col-12">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalKaryawan }} Karyawan</h3>
                    <p>Jumlah Karyawan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <!-- Card Jumlah Karyawan Absen Hari Ini -->
        <div class="col-lg-6 col-12">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $karyawanAbsenHariIni }} Karyawan</h3>
                    <p>Hadir Hari Ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="card-body bg-white">
            <h3>Data Absensi</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No. Anggota</th>
                                        <th>Nama Karyawan</th>
                                        <th>Total Jam Kerja</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($id=1)
                                    @if($data->count()>0)
                                    @foreach($data as $row)
                                        <tr>
                                            <td>{{ $id }}</td>
                                            <td>{{ $row->karyawan->nomor_anggota }}</td>
                                            <td>{{ $row->karyawan->nama }}</td>
                                            <td>@if($row->jumlah_jam_kerja!=null) {{ $row->jumlah_jam_kerja }} jam @else - @endif</td>
                                        </tr>
                                        @php($id++)
                                    @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada absensi</td>
                                        </tr>
                                    @endif
                                </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
