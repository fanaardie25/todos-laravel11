@extends('layout.app')

@section('title', 'register')


@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card p-4">
            <h1>Register</h1>
            <form action="" method="post">
                @csrf
                @include('layout.notif')
                <div class="mb-2">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email"  class="form-control" id="email" name="email" aria-describedby="emailHelp" value="{{ old('email') }}">
                </div>
                <div class="mb-2">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                </div>
                <h3>password</h3>
                <div class="mb-2">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
                <div class="mb-2">
                    <label for="password-confirmation" class="form-label">Konfirmasi password</label>
                    <input type="password" class="form-control" name="password-confirmation" id="password-confirmation">
                </div>
                <div class="d-inline">
                    <a href="{{ route('login') }}">Login</a> | <a href="{{ route('forgotpassword') }}">Lupa password?</a>
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection