@extends('adminlte::page')

@section('title', 'Profil Perusahaan')

@section('content_header')
    <h1>Profil Perusahaan</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <p><strong>Nama Perusahaan :</strong> {{ $profil->nama_perusahaan }}</p>
            <p><strong>Badan Hukum :</strong> {{ $profil->badan_hukum }}</p>
            <p><strong>Alamat :</strong> {{ $profil->alamat }}</p>
            <p><strong>No. Telp. :</strong> {{ $profil->no_telp }}</p>
            <p><strong>Area Kantor : </strong></p>
            <p><strong>A. Latitude : {{ $profil->latitude }}</strong></p>
            <p><strong>B. Longitude : {{ $profil->longitude }}</strong></p>
            <p><strong>C. Radius : {{ $profil->radius }} Meter</strong></p>
            <p><strong>Status Area : {{ $profil->status }}</strong></p>
            <a href="{{route('profilperusahaan.edit')}}" class="btn btn-warning btn-sm">Edit Profil</a>
        </div>
    </div>
@stop
