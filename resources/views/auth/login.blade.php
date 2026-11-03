@extends('layouts.auth')
@section('title', 'Masuk')
@section('content')
    <div class="auth-brand">
        <span class="brand__mark"><i class="fa-solid fa-house-chimney"></i></span>
        <div>
            <span class="auth-tag">Maply</span>
            <h1>Selamat Datang Kembali</h1>
        </div>
    </div>
    <p class="lead">Masuk untuk melanjutkan pencarian kos.</p>

    @if(session('success'))<div class="flash success" style="margin-bottom:16px"><i class="fa-solid fa-circle-check lead"></i><span>{{ session('success') }}</span></div>@endif
    @if(session('error'))<div class="flash error" style="margin-bottom:16px"><i class="fa-solid fa-circle-exclamation lead"></i><span>{{ session('error') }}</span></div>@endif
    @include('partials.form-errors')

    <form method="POST" action="{{ route('login.process') }}" class="auth-form" data-once novalidate>
        @csrf
        <div class="fgroup">
            <label for="login">Email atau username</label>
            <div class="input-icon">
                <i class="fa-regular fa-user"></i>
                <input class="input @error('login') is-invalid @enderror" id="login" name="login" type="text" value="{{ old('login') }}" placeholder="nama@email.com" autocomplete="username" autocapitalize="none" required autofocus>
            </div>
        </div>
        <div class="fgroup">
            <label for="password">Password</label>
            <div class="input-icon">
                <i class="fa-solid fa-lock"></i>
                <input class="input @error('password') is-invalid @enderror" id="password" name="password" type="password" placeholder="••••••••" autocomplete="current-password" required>
                <button type="button" class="reveal" data-toggle-password="password" aria-label="Tampilkan password"><i class="fa-regular fa-eye"></i></button>
            </div>
        </div>
        <div class="auth-links">
            <label class="check"><input type="checkbox" name="remember" value="1" @checked(old('remember'))> Ingat saya</label>
            <a href="{{ route('password.request') }}">Lupa password?</a>
        </div>
        <button type="submit" class="btn btn-primary btn-block"><i class="fa-solid fa-right-to-bracket"></i> Masuk</button>
    </form>

    <p class="switch">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
@endsection
