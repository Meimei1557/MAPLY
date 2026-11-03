
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" id="meta-theme-color" content="#10091F">
    <script>
        (function () {
            try {
                var t = localStorage.getItem('maply-theme');
                t = (t === 'dark' || t === 'light') ? t
                    : (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
                document.documentElement.setAttribute('data-theme', t);
                var m = document.getElementById('meta-theme-color');
                if (m) m.setAttribute('content', t === 'light' ? '#F4F1FB' : '#10091F');
            } catch (e) { /* fallback: tema gelap */ }
        })();
    </script>
    <title>Selamat Datang di MAPLY</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        :root {
            --night: #160D2A;
            --night-light: #301950;
            --lilac: #B56CFF;
            --cream: #F8F5FF;
            --glow-a: #493064;
            --glow-b: #302044;
            --grid-line: rgba(181, 108, 255, .05);
            --muted: #B5A8CB;
            --muted-deep: #8D7FA6;
        }

        /* Tema terang (mengikuti maply.css): dasar terang, aksen ungu tetap */
        [data-theme="light"] {
            --night: #F4F1FB;
            --night-light: #E6DCFA;
            --lilac: #6A28D9;
            --cream: #241733;
            --glow-a: #E4D6FB;
            --glow-b: #EFE8FC;
            --grid-line: rgba(106, 40, 217, .09);
            --muted: #5A5070;
            --muted-deep: #6B6280;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            background-image:
                linear-gradient(var(--grid-line) 1px, transparent 1px),
                linear-gradient(90deg, var(--grid-line) 1px, transparent 1px);
            background-size: 36px 36px;
        }

        body {
            margin: 0;
            min-height: 100vh;
            overflow: hidden;
            display: grid;
            place-items: center;
            color: var(--cream);
            background:
                radial-gradient(ellipse at 50% 43%, var(--glow-a) 0%, transparent 43%),
                radial-gradient(ellipse at 50% 100%, var(--glow-b) 0%, transparent 55%),
                var(--night);
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
        }

        .scene {
            position: relative;
            isolation: isolate;
            width: min(100%, 900px);
            min-height: 100svh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 28px 20px;
            text-align: center;
        }

        .glow {
            position: absolute;
            z-index: -1;
            top: 43%;
            left: 50%;
            width: min(72vw, 510px);
            aspect-ratio: 1;
            border-radius: 50%;
            background: #8B3DFF;
            filter: blur(100px);
            opacity: .12;
            transform: translate(-50%, -50%);
            animation: breathe 3s ease-in-out infinite alternate;
        }

        .brand {
            margin: 0 0 15px;
            color: var(--lilac);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .42em;
            animation: fade-down 1s both;
        }

        h1 {
            margin: 0;
            font-family: Georgia, 'Times New Roman', serif;
            font-weight: 400;
            font-size: clamp(34px, 7vw, 65px);
            letter-spacing: -.04em;
            animation: fade-up 1s .2s both;
        }

        h1 span { color: var(--lilac); font-style: italic; }

        .subtitle {
            margin: 13px 0 0;
            color: var(--muted);
            font-size: 14px;
            letter-spacing: .05em;
            animation: fade-up 1s .4s both;
        }

        .house-wrap {
            width: min(90vw, 520px);
            margin: 12px 0 0;
            filter: drop-shadow(0 0 24px rgba(197, 166, 240, .18));
            animation: house-arrive 1.5s .25s both;
        }

        .house {
            width: 100%;
            overflow: visible;
        }

        .draw {
            fill: none;
            stroke: var(--lilac);
            stroke-width: 2.1;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 1200;
            stroke-dashoffset: 1200;
            animation: draw-line 2.6s .5s ease forwards;
        }

        .house-fill {
            fill: #B56CFF;
            opacity: 0;
            animation: light-on 1.3s 2.5s ease forwards;
        }

        .window {
            fill: var(--lilac);
            opacity: 0;
            filter: drop-shadow(0 0 10px rgba(197, 166, 240, .8));
            animation: light-on .7s 2.3s ease forwards;
        }

        .orbit {
            fill: none;
            stroke: #B56CFF;
            stroke-width: .7;
            opacity: .2;
            transform-origin: 250px 175px;
            animation: orbit 15s linear infinite;
        }

        .spark {
            fill: #D9C2FF;
            transform-box: fill-box;
            transform-origin: center;
            animation: sparkle 2s ease-in-out infinite alternate;
        }

        .spark:nth-of-type(2n) { animation-delay: .7s; }
        .spark:nth-of-type(3n) { animation-delay: 1.2s; }

        .ground {
            width: min(65vw, 340px);
            height: 1px;
            margin: -3px 0 0;
            background: linear-gradient(90deg, transparent, #B56CFF, transparent);
            box-shadow: 0 0 20px rgba(197, 166, 240, .3);
            animation: ground-in 1.5s .7s both;
        }

        .loading {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-top: 35px;
            color: var(--muted);
            font-size: 11px;
            letter-spacing: .2em;
            text-transform: uppercase;
            animation: fade-up 1s 1s both;
        }

        .dots { display: flex; gap: 5px; }

        .dots i {
            display: block;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--lilac);
            animation: dot 1s infinite alternate;
        }

        .dots i:nth-child(2) { animation-delay: .2s; }
        .dots i:nth-child(3) { animation-delay: .4s; }

        .footer {
            position: absolute;
            bottom: 22px;
            color: var(--muted-deep);
            font-size: 10px;
            letter-spacing: .16em;
        }

        .scene.exit {
            animation: scene-exit .75s ease forwards;
        }

        @keyframes draw-line { to { stroke-dashoffset: 0; } }

        @keyframes light-on {
            from { opacity: 0; }
            to { opacity: .95; }
        }

        @keyframes fade-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fade-down {
            from { opacity: 0; transform: translateY(-14px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes house-arrive {
            from { opacity: 0; transform: translateY(28px) scale(.88); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes breathe {
            to { opacity: .24; transform: translate(-50%, -50%) scale(1.2); }
        }

        @keyframes orbit {
            to { transform: rotate(360deg); }
        }

        @keyframes sparkle {
            from { opacity: .15; transform: scale(.5); }
            to { opacity: 1; transform: scale(1.5); }
        }

        @keyframes ground-in {
            from { opacity: 0; transform: scaleX(.1); }
            to { opacity: 1; transform: scaleX(1); }
        }

        @keyframes dot {
            from { opacity: .25; transform: translateY(0); }
            to { opacity: 1; transform: translateY(-4px); }
        }

        @keyframes scene-exit {
            to { opacity: 0; transform: scale(1.04); }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>
<body>
    <main class="scene" id="scene">
        <div class="glow"></div>

        <p class="brand">M A P L Y</p>
        <h1>Temukan tempat untuk <span>pulang.</span></h1>
        <p class="subtitle">Setiap perjalanan dimulai dari sebuah rumah.</p>

        <div class="house-wrap">
            <svg class="house" viewBox="0 0 500 350"
                 xmlns="http://www.w3.org/2000/svg"
                 role="img" aria-label="Ilustrasi rumah bercahaya">
                <defs>
                    <linearGradient id="lilacFill" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0" stop-color="#D9C2FF"/>
                        <stop offset="1" stop-color="#6A28D9"/>
                    </linearGradient>
                </defs>

                <ellipse class="orbit" cx="250" cy="175" rx="215" ry="125"/>
                <ellipse class="orbit" cx="250" cy="175" rx="175" ry="95"
                         style="animation-direction:reverse"/>

                <g class="draw">
                    <path d="M95 175 L250 55 L405 175"/>
                    <path d="M125 160 V290 H375 V160"/>
                    <path d="M78 180 L250 45 L422 180"/>
                    <path d="M165 290 V205 Q165 190 180 190 H220 Q235 190 235 205 V290"/>
                    <path d="M275 200 H340 V250 H275 Z"/>
                    <path d="M307 200 V250 M275 225 H340"/>
                    <path d="M105 290 H395"/>
                    <path d="M65 310 H435"/>
                    <path d="M145 135 V100 H190 V100"/>
                    <path d="M310 111 L310 84 H350 L350 142"/>
                    <path d="M150 175 H200 M300 175 H350"/>
                </g>

                <path class="house-fill" fill="url(#lilacFill)"
                      d="M130 165 L250 75 L370 165 V282 H248 V207 Q248 195 235 195 H180 Q167 195 167 207 V282 H130 Z"/>

                <g class="window">
                    <rect x="281" y="203" width="52" height="40" rx="2"/>
                    <rect x="183" y="210" width="34" height="72" rx="2"/>
                </g>

                <g>
                    <circle class="spark" cx="94" cy="116" r="2.8"/>
                    <circle class="spark" cx="385" cy="118" r="2.5"/>
                    <circle class="spark" cx="115" cy="250" r="2"/>
                    <circle class="spark" cx="393" cy="248" r="3"/>
                    <circle class="spark" cx="220" cy="38" r="2"/>
                    <circle class="spark" cx="300" cy="48" r="2.5"/>
                    <circle class="spark" cx="65" cy="210" r="1.8"/>
                    <circle class="spark" cx="433" cy="190" r="2"/>
                </g>
            </svg>
        </div>

        <div class="ground"></div>

        <div class="loading">
            <span>Menyiapkan beranda</span>
            <span class="dots"><i></i><i></i><i></i></span>
        </div>

        <p class="footer">MENCARI KOS, MENEMUKAN RUMAH.</p>
    </main>

    <script>
        (() => {
            const scene = document.getElementById('scene');

            // Halaman ini menunggu 4,5 detik sebelum kembali ke beranda.
            window.setTimeout(() => {
                scene.classList.add('exit');

                window.setTimeout(() => {
                    window.location.replace(@json(route('dashboard')));
                }, 750);
            }, 4500);
        })();
    </script>
</body>
</html>
