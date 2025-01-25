@extends('adminlte::page')

@section('title', 'Laporan Absensi')

@section('content_header')
    <h1>Laporan Absensi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('absensi.generateLaporan') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="start_date">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                    @error('start_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_date">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                    @error('end_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Generate Laporan</button>
            </form>
        </div>
    </div>
@stop
