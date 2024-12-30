@extends('layout.app')

@section('title', 'reset password')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card p-4">
            <h1>reset password</h1>
            <form action="{{ route('forgotpassword.post') }}" method="post">
                @csrf
                @include('layout.notif')
                <div class="mb-2">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" value="{{ old('email') }}">
                </div>
                <div class="d-inline">
                    <a href="{{ route('login') }}">Login</a> | <a href="{{ route('register') }}">Register</a>
                    <button type="submit" class="btn btn-primary">Kirim link reset password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection