@extends('adminlte::page')

@section('title', 'Edit Jabatan')

@section('content_header')
    <h1>Edit Jabatan</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('jabatan.update', $jabatan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nama_jabatan">Nama Jabatan</label>
                    <input type="text" name="nama_jabatan" class="form-control @error('nama_jabatan') is-invalid @enderror" value="{{ old('nama_jabatan', $jabatan->nama_jabatan) }}">
                    @error('nama_jabatan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $jabatan->keterangan) }}</textarea>
                    @error('keterangan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
@stop
