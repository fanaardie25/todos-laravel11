@extends('layout.app')

@section('title', 'update-data')

@section('nav')
@include('layout.nav')
@endsection

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card p-4">
            <h1>Update Data</h1>
            <form action="{{ route('user.update-data.post') }}" method="post">
                @csrf
                @include('layout.notif')
                <div class="mb-2">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" disabled class="form-control" id="email" name="email" aria-describedby="emailHelp" value="{{ Auth::user()->email}}">
                </div>
                <div class="mb-2">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ? old('name') : Auth::user()->name }}">
                </div>
                <h3>password</h3>
                <div class="form-text">Silakan masukan password jika akan melakukan pergantian password</div>
                <div class="mb-2">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
                <div class="mb-2">
                    <label for="password-confirmation" class="form-label">Konfirmasi password</label>
                    <input type="password" class="form-control" name="password-confirmation" id="password-confirmation">
                </div>
                <div class="d-inline">
                    <button type="submit" class="btn btn-primary">Update data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection