@extends('adminlte::page')

@section('title', 'Edit Absensi')

@section('content_header')
    <h1>Edit Absensi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('absensi.update', $absensi->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="karyawan_id">Nama Karyawan</label>
                    <select name="karyawan_id" class="form-control @error('karyawan_id') is-invalid @enderror">
                        <option value="">Pilih Karyawan</option>
                        @foreach($karyawans as $karyawan)
                            <option value="{{ $karyawan->id }}" {{ old('karyawan_id', $absensi->karyawan_id) == $karyawan->id ? 'selected' : '' }}>{{ $karyawan->nama }}</option>
                        @endforeach
                    </select>
                    @error('karyawan_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tanggal">Tanggal </label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $absensi->tanggal->format('Y-m-d')) }}">
                    @error('tanggal')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jam_masuk">Jam Masuk</label>
                    <input type="time" name="jam_masuk" class="form-control @error('jam_masuk') is-invalid @enderror" value="{{ old('jam_masuk', $absensi->jam_masuk) }}">
                    @error('jam_masuk')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jam_keluar">Jam Keluar</label>
                    <input type="time" name="jam_keluar" class="form-control @error('jam_keluar') is-invalid @enderror" value="{{ old('jam_keluar', $absensi->jam_keluar) }}">
                    @error('jam_keluar')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@stop
