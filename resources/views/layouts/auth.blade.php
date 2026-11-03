<!DOCTYPE html>
<html lang="id">
<head>
    @include('layouts.head')
    <title>@yield('title', 'Maply') — Maply</title>
    @stack('head')
</head>
<body class="auth-body">
<div class="auth-page">
    {{-- Lapisan dekoratif di belakang kartu: titik koordinat, pin, garis, radar --}}
    <div class="auth-fx" id="authFx" aria-hidden="true">
        <svg class="fx-line" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 1000 1000">
            {{-- stroke mengikuti tema via currentColor (lihat .fx-line pada maply.css) --}}
            <polyline points="90,180 260,340 430,300 620,520 840,470" fill="none" stroke="currentColor" stroke-width="1.5" stroke-dasharray="7 8"/>
            <polyline points="140,760 320,640 560,730 780,600" fill="none" stroke="currentColor" stroke-width="1.5" stroke-dasharray="7 8"/>
        </svg>
        <div class="fx-dot" style="left:9%;top:18%" data-depth="18"></div>
        <div class="fx-dot fx-dot--soft" style="left:42%;top:10%" data-depth="30"></div>
        <div class="fx-dot" style="left:84%;top:30%" data-depth="22"></div>
        <div class="fx-dot" style="left:14%;top:76%" data-depth="34"></div>
        <div class="fx-dot fx-dot--soft" style="left:76%;top:82%" data-depth="26"></div>

        <span class="fx-pin-wrap" style="left:12%;top:30%" data-depth="40">
            <span class="fx-pin"><i class="fa-solid fa-location-dot"></i></span>
        </span>
        <span class="fx-pin-wrap" style="left:87%;top:62%" data-depth="46">
            <span class="fx-pin fx-pin--ok"><i class="fa-solid fa-house-chimney"></i></span>
        </span>
        <span class="fx-pin-wrap" style="left:8%;top:60%" data-depth="30">
            <span class="fx-pin fx-pin--warn"><i class="fa-solid fa-building-columns"></i></span>
        </span>
        <span class="fx-pulse" style="left:12%;top:30%" data-depth="40"></span>
        <span class="fx-pulse" style="left:87%;top:62%;animation-delay:1.2s" data-depth="46"></span>

        <div class="fx-radar" style="right:-120px;bottom:-140px;left:auto;top:auto" data-depth="14">
            <div class="fx-sweep"></div>
        </div>
        <div class="fx-radar" style="left:-150px;top:-160px;right:auto;bottom:auto;width:300px;height:300px" data-depth="10">
            <div class="fx-sweep" style="animation-direction:reverse"></div>
        </div>
    </div>

    <div class="auth-card-wrap reveal-up">
        <div class="auth-card">
            {{-- Wrapper stagger: setiap anak kartu muncul berjenjang (animasi CSS, lihat maply.css) --}}
            <div class="stagger">
                @yield('content')
            </div>
        </div>
    </div>

    <button type="button" class="theme-toggle auth-theme" data-theme-toggle aria-label="Ganti tema terang/gelap" aria-pressed="false">
        <i class="fa-solid fa-moon icon-moon" aria-hidden="true"></i>
        <i class="fa-solid fa-sun icon-sun" aria-hidden="true"></i>
    </button>
</div>
<script src="{{ asset('js/maply.js') }}"></script>
</body>
</html>
