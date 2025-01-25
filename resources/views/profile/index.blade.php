@extends('adminlte::page')

@section('title', 'Profil Akun')

@section('content_header')
    <h1>Profil Akun</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <a href="{{ route('profile.edit') }}" class="btn btn-warning btn-sm">Edit Profil</a>
        </div>
    </div>
@stop
