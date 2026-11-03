<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" id="meta-theme-color" content="#10091F">
@php
    // Dipakai skrip tema di bawah; sesuai palet maply.css (dark default).
    $maplyThemeColors = ['dark' => '#10091F', 'light' => '#F4F1FB'];
@endphp
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/maply.css') }}">
{{-- Tema diterapkan SEBELUM render body agar tidak berkedip (poin 1). --}}
<script>
(function () {
    try {
        var tersimpan = localStorage.getItem('maply-theme');
        var tema = (tersimpan === 'dark' || tersimpan === 'light')
            ? tersimpan
            : (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
        document.documentElement.setAttribute('data-theme', tema);
        var meta = document.getElementById('meta-theme-color');
        if (meta) meta.setAttribute('content', tema === 'light' ? '{!! $maplyThemeColors['light'] !!}' : '{!! $maplyThemeColors['dark'] !!}');
    } catch (e) { /* localStorage/JS dimatikan: pakai tema gelap default */ }
})();
</script>
