@extends('layouts.auth')
@section('title', 'Lupa Password')
@section('content')
    <div class="auth-brand">
        <span class="brand__mark"><i class="fa-solid fa-house-chimney"></i></span>
        <div>
            <span class="auth-tag">Maply</span>
            <h1>Lupa Password?</h1>
        </div>
    </div>
    <p class="lead">Pemulihan lewat email belum tersedia.</p>

    <div class="notice notice--info" style="margin-bottom:18px">
        <i class="fa-solid fa-circle-info"></i>
        <div>Hubungi administrator Maply untuk mengatur ulang password Anda. Administrator server dapat melakukannya lewat
            <code>php artisan tinker</code>, atau mengaktifkan reset email setelah <code>MAIL_*</code> di <code>.env</code> diisi.</div>
    </div>

    <a href="{{ route('login') }}" class="btn btn-primary btn-block"><i class="fa-solid fa-arrow-left"></i> Kembali ke halaman masuk</a>
@endsection
