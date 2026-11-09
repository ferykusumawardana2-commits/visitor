<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Si Kunjung Pemda') — Portal Kunjungan &amp; Audiensi</title>
    <script>
        (function () {
            const savedTheme = localStorage.getItem('sikunjung_theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;0,9..144,800;1,9..144,600&family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-paper: #F5F2EB;
            --bg-card: #FFFDF9;
            --bg-subtle: #EFECE4;
            --ink-primary: #12201B;
            --ink-muted: #54615B;
            --accent-terra: #C8552B;
            --accent-jade: #1B6B53;
            --accent-gold: #C8963E;
            --line-rule: #D6CFC0;
            --border-strong: #12201B;
            --shadow-color: #12201B;
        }
        html.dark {
            --bg-paper: #0B1310;
            --bg-card: #13201B;
            --bg-subtle: #1A2B24;
            --ink-primary: #F3EFE6;
            --ink-muted: #9BA8A1;
            --accent-terra: #E06D42;
            --accent-jade: #2BB689;
            --accent-gold: #E2B155;
            --line-rule: #2A4037;
            --border-strong: #3B564B;
            --shadow-color: #C8963E;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-paper);
            color: var(--ink-primary);
            background-image: radial-gradient(rgba(18, 32, 27, 0.06) 1px, transparent 1px);
            background-size: 24px 24px;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        html.dark body {
            background-image: radial-gradient(rgba(200, 150, 62, 0.08) 1px, transparent 1px);
        }
        .font-serif-display {
            font-family: 'Fraunces', Georgia, serif;
        }
        .font-mono-code {
            font-family: 'JetBrains Mono', monospace;
        }
        .civic-card {
            background-color: var(--bg-card);
            border: 1.5px solid var(--border-strong);
            box-shadow: 5px 5px 0px var(--shadow-color);
            transition: transform 0.15s ease, box-shadow 0.15s ease, background-color 0.2s ease, border-color 0.2s ease;
        }
        .civic-card-soft {
            background-color: var(--bg-card);
            border: 1.5px solid var(--border-strong);
            box-shadow: 4px 4px 0px rgba(18, 32, 27, 0.16);
        }
        .civic-card-hover:hover {
            transform: translate(-2px, -2px);
            box-shadow: 7px 7px 0px #C8552B;
        }
        .civic-btn-primary {
            background-color: #12201B;
            color: #FFFDF9;
            border: 1.5px solid var(--border-strong);
            box-shadow: 3px 3px 0px #C8552B;
            transition: all 0.15s ease;
        }
        html.dark .civic-btn-primary {
            background-color: #C8963E;
            color: #0B1310;
            border-color: #F3EFE6;
            box-shadow: 3px 3px 0px #C8552B;
        }
        .civic-btn-primary:hover {
            background-color: #C8552B !important;
            color: #FFFDF9 !important;
            box-shadow: 4px 4px 0px var(--border-strong);
            transform: translate(-1px, -1px);
        }
        .civic-btn-terra {
            background-color: #C8552B;
            color: #FFFDF9;
            border: 1.5px solid var(--border-strong);
            box-shadow: 3px 3px 0px var(--shadow-color);
            transition: all 0.15s ease;
        }
        .civic-btn-terra:hover {
            background-color: #12201B;
            color: #FFFDF9;
            transform: translate(-1px, -1px);
        }
        html.dark .civic-btn-terra:hover {
            background-color: #C8963E;
            color: #0B1310;
        }
        .ticket-notch {
            position: relative;
        }
        .ticket-notch::before,
        .ticket-notch::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 14px;
            height: 14px;
            background-color: var(--bg-paper);
            border: 1.5px solid var(--border-strong);
            border-radius: 9999px;
            transform: translateY(-50%);
        }
        .ticket-notch::before { left: -8px; }
        .ticket-notch::after { right: -8px; }

        /* Dark Mode Overrides for Custom Utility Classes */
        html.dark header.sticky {
            background-color: rgba(11, 19, 16, 0.95) !important;
            border-color: #3B564B !important;
        }
        html.dark .bg-\[\#FFFDF9\],
        html.dark .bg-white {
            background-color: #13201B !important;
        }
        html.dark .bg-\[\#F5F2EB\],
        html.dark .bg-\[\#F5F2EB\]\/60,
        html.dark .bg-\[\#EFECE4\],
        html.dark .bg-slate-50,
        html.dark .bg-slate-100 {
            background-color: #1A2B24 !important;
        }
        html.dark .text-\[\#12201B\],
        html.dark .text-slate-900,
        html.dark .text-slate-800,
        html.dark .text-slate-700 {
            color: #F3EFE6 !important;
        }
        html.dark .text-\[\#54615B\],
        html.dark .text-slate-600,
        html.dark .text-slate-500 {
            color: #9BA8A1 !important;
        }
        html.dark .text-\[\#C8552B\] {
            color: #F08056 !important;
        }
        html.dark .text-\[\#1B6B53\] {
            color: #4ADE80 !important;
        }
        html.dark .border-\[\#12201B\],
        html.dark .border-\[\#D6CFC0\],
        html.dark .border-slate-200,
        html.dark .border-slate-300 {
            border-color: #3B564B !important;
        }
        html.dark .divide-\[\#12201B\] > :not([hidden]) ~ :not([hidden]),
        html.dark .divide-\[\#D6CFC0\] > :not([hidden]) ~ :not([hidden]),
        html.dark .divide-slate-200 > :not([hidden]) ~ :not([hidden]) {
            border-color: #2A4037 !important;
        }
        html.dark .bg-\[\#E6F4EE\] {
            background-color: #0F382B !important;
            color: #6EE7B7 !important;
        }
        html.dark .bg-\[\#FCEAE6\] {
            background-color: #3B1812 !important;
            color: #FDA4AF !important;
        }
        html.dark .bg-\[\#FFF8E6\] {
            background-color: #38280E !important;
            color: #FCD34D !important;
        }
        html.dark input,
        html.dark select,
        html.dark textarea {
            background-color: #0E1814 !important;
            color: #F3EFE6 !important;
            border-color: #3B564B !important;
        }
        html.dark input::placeholder,
        html.dark textarea::placeholder {
            color: #6E8078 !important;
        }
        html.dark nav .bg-\[\#12201B\] {
            background-color: #C8552B !important;
            color: #FFFDF9 !important;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col selection:bg-[#C8552B] selection:text-white">
    <!-- Top Civic Gazette Bar -->
    <div class="bg-[#12201B] text-[#E6E1D6] border-b-2 border-[#12201B] text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-2 px-2.5 py-0.5 bg-[#1B6B53] text-white font-mono-code text-[11px] font-semibold uppercase tracking-wider border border-emerald-400/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-ping"></span>
                    PORTAL AKTIF
                </span>
                <span class="font-mono-code text-[11px] text-[#C8963E] hidden sm:inline">
                    // GERBANG LAYANAN KUNJUNGAN &amp; AUDIENSI TERPADU
                </span>
            </div>

            <div class="flex items-center gap-4 font-mono-code text-[11px]">
                <span class="hidden md:inline text-stone-400">{{ now()->format('d.m.Y // H:i') }} WIB</span>
                <span class="hidden md:inline text-stone-600">|</span>
                @if(session('penduduk_id'))
                    @if(session('penduduk_status') === 'Admin')
                        <a href="{{ route('admin.kunjungan.index') }}" class="text-[#C8963E] hover:text-white font-bold underline decoration-dotted">
                            [MEJA ADMIN: {{ strtoupper(session('penduduk_nama')) }}]
                        </a>
                    @else
                        <a href="{{ route('karyawan.kunjungan.index') }}" class="text-emerald-300 hover:text-white font-bold underline decoration-dotted">
                            [PORTAL APARATUR: {{ strtoupper(session('penduduk_nama')) }}]
                        </a>
                    @endif
                @else
                    <a href="{{ route('admin.login') }}" class="hover:text-[#C8963E] transition">[LOGIN ADMIN]</a>
                    <a href="{{ route('karyawan.login') }}" class="hover:text-[#C8963E] transition">[LOGIN KARYAWAN]</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Architectural Masthead & Navigation -->
    <header class="sticky top-0 z-40 bg-[#F5F2EB]/95 backdrop-blur-md border-b-2 border-[#12201B]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="min-h-[4.5rem] py-3 flex flex-wrap items-center justify-between gap-4">
                <!-- Brand Emblem -->
                <a href="{{ route('landing.home') }}" class="flex items-center gap-3.5 group">
                    <div class="w-12 h-12 bg-[#12201B] text-[#FFFDF9] border-2 border-[#12201B] shadow-[3px_3px_0px_#C8552B] flex flex-col items-center justify-center group-hover:bg-[#C8552B] transition">
                        <span class="font-serif-display font-extrabold text-base leading-none tracking-tighter">SK</span>
                        <span class="font-mono-code text-[8px] tracking-widest text-[#C8963E] group-hover:text-white mt-0.5">PEMDA</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-serif-display text-xl sm:text-2xl font-bold tracking-tight text-[#12201B] leading-none">
                                Si Kunjung Pemda
                            </span>
                        </div>
                        <span class="font-mono-code text-[10px] uppercase tracking-widest text-[#54615B] block mt-1">
                            Sistem Registrasi &amp; Agenda Audiensi Publik
                        </span>
                    </div>
                </a>

                <!-- Indexed Editorial Navigation -->
                <nav class="hidden lg:flex items-center bg-[#FFFDF9] border-[1.5px] border-[#12201B] shadow-[3px_3px_0px_#12201B] divide-x-[1.5px] divide-[#12201B]">
                    <a href="{{ route('landing.home') }}"
                       class="px-4 py-2.5 text-xs font-bold tracking-wide transition flex items-center gap-2 {{ request()->routeIs('landing.home') ? 'bg-[#12201B] text-[#FFFDF9]' : 'text-[#12201B] hover:bg-[#EFECE4]' }}">
                        <span class="font-mono-code text-[10px] {{ request()->routeIs('landing.home') ? 'text-[#C8963E]' : 'text-[#C8552B]' }}">01</span>
                        <span>Beranda</span>
                    </a>
                    <a href="{{ route('landing.jadwal') }}"
                       class="px-4 py-2.5 text-xs font-bold tracking-wide transition flex items-center gap-2 {{ request()->routeIs('landing.jadwal') ? 'bg-[#12201B] text-[#FFFDF9]' : 'text-[#12201B] hover:bg-[#EFECE4]' }}">
                        <span class="font-mono-code text-[10px] {{ request()->routeIs('landing.jadwal') ? 'text-[#C8963E]' : 'text-[#C8552B]' }}">02</span>
                        <span>Jadwal Kunjungan</span>
                    </a>
                    <a href="{{ route('landing.struktur') }}"
                       class="px-4 py-2.5 text-xs font-bold tracking-wide transition flex items-center gap-2 {{ request()->routeIs('landing.struktur') ? 'bg-[#12201B] text-[#FFFDF9]' : 'text-[#12201B] hover:bg-[#EFECE4]' }}">
                        <span class="font-mono-code text-[10px] {{ request()->routeIs('landing.struktur') ? 'text-[#C8963E]' : 'text-[#C8552B]' }}">03</span>
                        <span>Struktur Karyawan</span>
                    </a>
                    @if(session('pemohon_id'))
                        <a href="{{ route('pemohon.kunjungan.index') }}"
                           class="px-4 py-2.5 text-xs font-bold tracking-wide transition flex items-center gap-2 {{ request()->routeIs('pemohon.kunjungan.*') ? 'bg-[#12201B] text-[#FFFDF9]' : 'text-[#12201B] hover:bg-[#EFECE4]' }}">
                            <span class="font-mono-code text-[10px] {{ request()->routeIs('pemohon.kunjungan.*') ? 'text-[#C8963E]' : 'text-[#C8552B]' }}">04</span>
                            <span>Pengajuan Saya</span>
                        </a>
                        <a href="{{ route('pemohon.histori') }}"
                           class="px-4 py-2.5 text-xs font-bold tracking-wide transition flex items-center gap-2 {{ request()->routeIs('pemohon.histori') ? 'bg-[#12201B] text-[#FFFDF9]' : 'text-[#12201B] hover:bg-[#EFECE4]' }}">
                            <span class="font-mono-code text-[10px] {{ request()->routeIs('pemohon.histori') ? 'text-[#C8963E]' : 'text-[#C8552B]' }}">05</span>
                            <span>Arsip Histori</span>
                        </a>
                    @endif
                </nav>

                <!-- Theme Toggle + Visitor Pass Action -->
                <div class="flex items-center gap-2.5">
                    <!-- Dark / Light Mode Toggle Button -->
                    <button type="button" onclick="toggleSikunjungTheme()"
                            title="Ubah Mode Gelap / Terang"
                            class="px-3 py-2 bg-[#FFFDF9] hover:bg-[#EFECE4] text-[#12201B] border-[1.5px] border-[#12201B] shadow-[2px_2px_0px_#C8552B] font-mono-code text-xs font-bold uppercase flex items-center gap-1.5 cursor-pointer transition">
                        <span id="theme-icon-sun" class="hidden text-amber-400">&#9728;</span>
                        <span id="theme-icon-moon" class="inline text-[#C8552B]">&#9790;</span>
                        <span id="theme-label" class="hidden sm:inline text-[11px]">DARK</span>
                    </button>

                    @if(session('pemohon_id'))
                        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-[#FFFDF9] border-[1.5px] border-[#12201B] text-xs">
                            <span class="w-2 h-2 rounded-full bg-[#1B6B53]"></span>
                            <span class="font-mono-code text-[11px] uppercase text-[#54615B]">Tamu:</span>
                            <strong class="font-bold text-[#12201B]">{{ session('pemohon_nama') }}</strong>
                        </div>
                        <form action="{{ route('pemohon.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3.5 py-2 bg-[#FFFDF9] hover:bg-rose-700 hover:text-white text-rose-600 border-[1.5px] border-[#12201B] font-mono-code text-xs font-bold uppercase transition cursor-pointer">
                                Keluar [X]
                            </button>
                        </form>
                    @else
                        <a href="{{ route('pemohon.login') }}" class="px-4 py-2 bg-[#FFFDF9] hover:bg-[#EFECE4] text-[#12201B] border-[1.5px] border-[#12201B] text-xs font-bold uppercase tracking-wider transition">
                            Masuk Tamu
                        </a>
                        <a href="{{ route('pemohon.register') }}" class="px-4 py-2 civic-btn-terra text-xs font-bold uppercase tracking-wider">
                            + Registrasi Pemohon
                        </a>
                    @endif
                </div>
            </div>

            <!-- Mobile Strip -->
            <div class="lg:hidden py-2 border-t border-[#D6CFC0] flex flex-wrap gap-1.5 text-xs font-bold">
                <a href="{{ route('landing.home') }}" class="px-2.5 py-1 border border-[#12201B] {{ request()->routeIs('landing.home') ? 'bg-[#12201B] text-white' : 'bg-[#FFFDF9]' }}">01. Beranda</a>
                <a href="{{ route('landing.jadwal') }}" class="px-2.5 py-1 border border-[#12201B] {{ request()->routeIs('landing.jadwal') ? 'bg-[#12201B] text-white' : 'bg-[#FFFDF9]' }}">02. Jadwal</a>
                <a href="{{ route('landing.struktur') }}" class="px-2.5 py-1 border border-[#12201B] {{ request()->routeIs('landing.struktur') ? 'bg-[#12201B] text-white' : 'bg-[#FFFDF9]' }}">03. Struktur</a>
                @if(session('pemohon_id'))
                    <a href="{{ route('pemohon.kunjungan.index') }}" class="px-2.5 py-1 border border-[#12201B] {{ request()->routeIs('pemohon.kunjungan.*') ? 'bg-[#12201B] text-white' : 'bg-[#FFFDF9]' }}">04. Kunjungan</a>
                    <a href="{{ route('pemohon.histori') }}" class="px-2.5 py-1 border border-[#12201B] {{ request()->routeIs('pemohon.histori') ? 'bg-[#12201B] text-white' : 'bg-[#FFFDF9]' }}">05. Histori</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Notifications -->
    @if(session('success') || session('error') || $errors->any())
        <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-6">
            @if(session('success'))
                <div class="p-4 bg-[#E6F4EE] border-2 border-[#12201B] shadow-[4px_4px_0px_#1B6B53] text-[#12201B] text-sm font-semibold flex items-center gap-3">
                    <span class="px-2 py-0.5 bg-[#1B6B53] text-white font-mono-code text-xs font-bold">BERHASIL</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-[#FCEAE6] border-2 border-[#12201B] shadow-[4px_4px_0px_#C8552B] text-[#12201B] text-sm font-semibold flex items-center gap-3">
                    <span class="px-2 py-0.5 bg-[#C8552B] text-white font-mono-code text-xs font-bold">PERHATIAN</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="p-4 bg-[#FCEAE6] border-2 border-[#12201B] shadow-[4px_4px_0px_#C8552B] text-[#12201B] text-sm">
                    <p class="font-mono-code text-xs font-bold uppercase text-[#C8552B] mb-1">// KOREKSI FORMULIR DIPERLUKAN:</p>
                    <ul class="list-disc list-inside space-y-0.5 font-medium">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Architectural Footer -->
    <footer class="bg-[#12201B] text-[#D6CFC0] mt-20 border-t-4 border-[#C8552B]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-12 gap-8">
            <div class="md:col-span-5 space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#C8552B] text-white font-serif-display font-bold flex items-center justify-center border border-white/20">
                        SK
                    </div>
                    <span class="font-serif-display text-xl font-bold text-white">Si Kunjung Pemda</span>
                </div>
                <p class="text-xs text-stone-400 leading-relaxed max-w-md">
                    Buku Tamu Digital &amp; Sistem Penjadwalan Audiensi Terpadu Pemerintah Daerah. Menghubungkan masyarakat dan pemohon dengan jadwal ketersediaan serta agenda kegiatan aparatur secara transparan.
                </p>
                <p class="font-mono-code text-[11px] text-[#C8963E] pt-1">
                    STATUS SISTEM: ONLINE // DATABASE TERINTEGRASI
                </p>
            </div>

            <div class="md:col-span-3 space-y-2">
                <h4 class="font-mono-code text-xs font-bold uppercase tracking-widest text-[#C8963E]">Indeks Halaman</h4>
                <ul class="space-y-1.5 text-xs font-medium">
                    <li><a href="{{ route('landing.home') }}" class="hover:text-white transition">01 / Beranda Utama</a></li>
                    <li><a href="{{ route('landing.jadwal') }}" class="hover:text-white transition">02 / Papan Jadwal Kunjungan</a></li>
                    <li><a href="{{ route('landing.struktur') }}" class="hover:text-white transition">03 / Direktori Struktur Aparatur</a></li>
                </ul>
            </div>

            <div class="md:col-span-4 space-y-2">
                <h4 class="font-mono-code text-xs font-bold uppercase tracking-widest text-[#C8963E]">Gerbang Otorisasi</h4>
                <div class="grid grid-cols-2 gap-2 pt-1 text-xs">
                    <a href="{{ route('pemohon.register') }}" class="p-2.5 border border-stone-700 hover:border-[#C8963E] hover:text-white transition">
                        <span class="font-mono-code text-[10px] text-stone-400 block">TAMU BARU</span>
                        <span class="font-bold">Daftar Pemohon &rarr;</span>
                    </a>
                    <a href="{{ route('pemohon.login') }}" class="p-2.5 border border-stone-700 hover:border-[#C8963E] hover:text-white transition">
                        <span class="font-mono-code text-[10px] text-stone-400 block">AKSES TAMU</span>
                        <span class="font-bold">Login Pemohon &rarr;</span>
                    </a>
                    <a href="{{ route('admin.login') }}" class="p-2.5 border border-stone-700 hover:border-[#C8963E] hover:text-white transition">
                        <span class="font-mono-code text-[10px] text-stone-400 block">OTORITAS</span>
                        <span class="font-bold">Meja Admin &rarr;</span>
                    </a>
                    <a href="{{ route('karyawan.login') }}" class="p-2.5 border border-stone-700 hover:border-[#C8963E] hover:text-white transition">
                        <span class="font-mono-code text-[10px] text-stone-400 block">PEJABAT / STAF</span>
                        <span class="font-bold">Portal Karyawan &rarr;</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="border-t border-stone-800 py-4 px-4 text-center font-mono-code text-[11px] text-stone-500">
            &copy; {{ date('Y') }} SI KUNJUNG PEMDA — TATA KELOLA PELAYANAN PUBLIK &amp; KUNJUNGAN TAMU
        </div>
    </footer>

    <script>
        function syncThemeButtonUI() {
            const isDark = document.documentElement.classList.contains('dark');
            const sunIcon = document.getElementById('theme-icon-sun');
            const moonIcon = document.getElementById('theme-icon-moon');
            const label = document.getElementById('theme-label');
            if (sunIcon && moonIcon && label) {
                if (isDark) {
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                    label.textContent = 'LIGHT';
                } else {
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                    label.textContent = 'DARK';
                }
            }
        }

        function toggleSikunjungTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('sikunjung_theme', isDark ? 'dark' : 'light');
            syncThemeButtonUI();
        }

        document.addEventListener('DOMContentLoaded', syncThemeButtonUI);
    </script>
</body>
</html>
