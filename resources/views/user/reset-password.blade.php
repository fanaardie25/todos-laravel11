@extends('layout.app')

@section('title', 'Reset password')


@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card p-4">
            <h1>Reset password</h1>
            <form action="{{ route('resetpassword.post') }}" method="post">
                <input type="hidden" name="token" value="{{ $token }}">
                @csrf
                @include('layout.notif')
                <div class="mb-2">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
                <div class="mb-2">
                    <label for="password-confirmation" class="form-label">Konfirmasi password</label>
                    <input type="password" class="form-control" name="password-confirmation" id="password-confirmation">
                </div>
                <div class="d-inline">
                    <button type="submit" class="btn btn-primary">Reset password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection