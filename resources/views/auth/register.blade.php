@extends('layouts.auth')
@section('title', 'Daftar')
@section('content')
    <div class="auth-brand">
        <span class="brand__mark"><i class="fa-solid fa-house-chimney"></i></span>
        <div>
            <span class="auth-tag">Maply</span>
            <h1>Buat Akun Baru</h1>
        </div>
    </div>
    <p class="lead">Gratis, untuk pencari kos.</p>

    @include('partials.form-errors')

    <form method="POST" action="{{ route('register.process') }}" class="auth-form" data-once>
        @csrf
        <div class="fgroup">
            <label for="name">Nama lengkap <span class="req">*</span></label>
            <input class="input @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Nama Anda" autocomplete="name" minlength="3" maxlength="100" required autofocus>
            @error('name')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="fgroup">
            <label for="username">Username <span class="req">*</span></label>
            <input class="input @error('username') is-invalid @enderror" id="username" name="username" type="text" value="{{ old('username') }}" placeholder="username_anda" autocomplete="username" autocapitalize="none" minlength="3" maxlength="30" pattern="[a-zA-Z0-9_.\-]+" required>
            <span class="help">3–30 karakter: huruf, angka, titik, strip, atau garis bawah.</span>
            @error('username')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="fgroup">
            <label for="email">Email <span class="req">*</span></label>
            <input class="input @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" required>
            @error('email')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="fgroup">
            <label for="password">Password <span class="req">*</span></label>
            <div class="input-icon">
                <i class="fa-solid fa-lock"></i>
                <input class="input @error('password') is-invalid @enderror" id="password" name="password" type="password" placeholder="Minimal 8 karakter" autocomplete="new-password" minlength="8" required>
                <button type="button" class="reveal" data-toggle-password="password" aria-label="Tampilkan password"><i class="fa-regular fa-eye"></i></button>
            </div>
            <span class="help">Minimal 8 karakter, mengandung huruf dan angka.</span>
            @error('password')<span class="err">{{ $message }}</span>@enderror
        </div>
        <div class="fgroup">
            <label for="password_confirmation">Ulangi password <span class="req">*</span></label>
            <input class="input" id="password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi password" autocomplete="new-password" minlength="8" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block"><i class="fa-solid fa-user-plus"></i> Daftar</button>
    </form>
    <p class="switch">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
@endsection
