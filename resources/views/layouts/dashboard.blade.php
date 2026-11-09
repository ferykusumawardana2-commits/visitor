<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Meja Kerja') — Si Kunjung Pemda</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;0,9..144,800&family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F5F2EB;
            color: #12201B;
            background-image: radial-gradient(rgba(18, 32, 27, 0.05) 1px, transparent 1px);
            background-size: 22px 22px;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        html.dark body {
            background-color: #0B1310;
            color: #F3EFE6;
            background-image: radial-gradient(rgba(200, 150, 62, 0.08) 1px, transparent 1px);
        }
        .font-serif-display { font-family: 'Fraunces', Georgia, serif; }
        .font-mono-code { font-family: 'JetBrains Mono', monospace; }
        main .bg-white.rounded-2xl {
            background-color: #FFFDF9 !important;
            border-radius: 0px !important;
            border: 1.5px solid #12201B !important;
            box-shadow: 4px 4px 0px #12201B !important;
        }
        html.dark main .bg-white.rounded-2xl,
        html.dark .bg-\[\#FFFDF9\],
        html.dark .bg-white {
            background-color: #13201B !important;
            border-color: #3B564B !important;
            box-shadow: 4px 4px 0px #C8963E !important;
        }
        html.dark .bg-\[\#F5F2EB\],
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
        html.dark .text-slate-500,
        html.dark .text-slate-400 {
            color: #9BA8A1 !important;
        }
        html.dark .border-\[\#12201B\],
        html.dark .border-slate-200,
        html.dark .border-slate-300 {
            border-color: #3B564B !important;
        }
        html.dark .divide-slate-200 > :not([hidden]) ~ :not([hidden]) {
            border-color: #2A4037 !important;
        }
        html.dark input,
        html.dark select,
        html.dark textarea {
            background-color: #0E1814 !important;
            color: #F3EFE6 !important;
            border-color: #3B564B !important;
        }
        main .rounded-xl, main .rounded-lg {
            border-radius: 0px !important;
        }
        main h2, main h3 {
            font-family: 'Fraunces', Georgia, serif;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row">
    <!-- Left Architectural Command Rail -->
    <aside class="w-full md:w-72 bg-[#12201B] text-[#E6E1D6] flex-shrink-0 flex flex-col justify-between border-r-2 border-[#12201B]">
        <div>
            <!-- Brand Header -->
            <div class="p-5 border-b border-stone-800 flex items-center justify-between">
                <a href="{{ route('landing.home') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#C8552B] text-white font-serif-display font-extrabold flex items-center justify-center border border-white/20 shadow-[2px_2px_0px_#C8963E]">
                        SK
                    </div>
                    <div>
                        <span class="font-serif-display font-bold text-lg text-white block leading-none">Si Kunjung Pemda</span>
                        <span class="font-mono-code text-[10px] text-[#C8963E] uppercase tracking-widest">
                            {{ session('penduduk_status') === 'Admin' ? '// MEJA KENDALI ADMIN' : '// PORTAL APARATUR' }}
                        </span>
                    </div>
                </a>
            </div>

            <!-- Active Officer Badge -->
            <div class="p-4 m-4 bg-[#192B24] border border-stone-700/80 relative">
                <div class="flex items-center justify-between mb-1">
                    <span class="font-mono-code text-[10px] uppercase tracking-wider text-stone-400">PETUGAS AKTIF</span>
                    <span class="px-2 py-0.5 font-mono-code text-[10px] font-bold uppercase bg-[#C8552B] text-white">
                        {{ session('penduduk_status') === 'Admin' ? 'ADMIN' : 'KARYAWAN' }}
                    </span>
                </div>
                <p class="font-serif-display text-base font-bold text-white truncate">{{ session('penduduk_nama') }}</p>
            </div>

            <!-- Indexed Navigation -->
            <nav class="px-4 py-2 space-y-1.5 text-xs font-bold">
                @if(session('penduduk_status') === 'Admin')
                    <p class="px-2 pt-2 pb-1 font-mono-code text-[10px] uppercase tracking-widest text-[#C8963E]">
                        [A] OTORITAS ADMINISTRATOR
                    </p>
                    <a href="{{ route('admin.kunjungan.index') }}"
                       class="flex items-center justify-between px-3.5 py-3 border transition {{ request()->routeIs('admin.kunjungan.*') ? 'bg-[#C8552B] text-white border-[#C8963E]' : 'border-stone-800 text-stone-300 hover:border-stone-600 hover:bg-[#192B24]' }}">
                        <span>01 / Pengajuan Kunjungan</span>
                        <span class="font-mono-code text-[10px]">&rarr;</span>
                    </a>
                    <a href="{{ route('admin.settings-kunjungan.index') }}"
                       class="flex items-center justify-between px-3.5 py-3 border transition {{ request()->routeIs('admin.settings-kunjungan.*') ? 'bg-[#C8552B] text-white border-[#C8963E]' : 'border-stone-800 text-stone-300 hover:border-stone-600 hover:bg-[#192B24]' }}">
                        <span>02 / Setting Kunjungan</span>
                        <span class="font-mono-code text-[10px]">&rarr;</span>
                    </a>
                    <a href="{{ route('admin.kegiatan.index') }}"
                       class="flex items-center justify-between px-3.5 py-3 border transition {{ request()->routeIs('admin.kegiatan.*') ? 'bg-[#C8552B] text-white border-[#C8963E]' : 'border-stone-800 text-stone-300 hover:border-stone-600 hover:bg-[#192B24]' }}">
                        <span>03 / Agenda Kegiatan</span>
                        <span class="font-mono-code text-[10px]">&rarr;</span>
                    </a>
                    <a href="{{ route('admin.karyawan.index') }}"
                       class="flex items-center justify-between px-3.5 py-3 border transition {{ request()->routeIs('admin.karyawan.*') ? 'bg-[#C8552B] text-white border-[#C8963E]' : 'border-stone-800 text-stone-300 hover:border-stone-600 hover:bg-[#192B24]' }}">
                        <span>04 / Struktur &amp; Jabatan</span>
                        <span class="font-mono-code text-[10px]">&rarr;</span>
                    </a>
                @endif

                <p class="px-2 pt-4 pb-1 font-mono-code text-[10px] uppercase tracking-widest text-emerald-400">
                    [B] PANEL KARYAWAN / PEJABAT
                </p>
                <a href="{{ route('karyawan.kunjungan.index') }}"
                   class="flex items-center justify-between px-3.5 py-3 border transition {{ request()->routeIs('karyawan.kunjungan.*') ? 'bg-[#1B6B53] text-white border-emerald-400' : 'border-stone-800 text-stone-300 hover:border-stone-600 hover:bg-[#192B24]' }}">
                    <span>01 / Kunjungan Masuk</span>
                    <span class="font-mono-code text-[10px]">&rarr;</span>
                </a>
                <a href="{{ route('karyawan.settings-kunjungan.index') }}"
                   class="flex items-center justify-between px-3.5 py-3 border transition {{ request()->routeIs('karyawan.settings-kunjungan.*') ? 'bg-[#1B6B53] text-white border-emerald-400' : 'border-stone-800 text-stone-300 hover:border-stone-600 hover:bg-[#192B24]' }}">
                    <span>02 / Setting Kunjungan Saya</span>
                    <span class="font-mono-code text-[10px]">&rarr;</span>
                </a>
                <a href="{{ route('karyawan.kegiatan.index') }}"
                   class="flex items-center justify-between px-3.5 py-3 border transition {{ request()->routeIs('karyawan.kegiatan.*') ? 'bg-[#1B6B53] text-white border-emerald-400' : 'border-stone-800 text-stone-300 hover:border-stone-600 hover:bg-[#192B24]' }}">
                    <span>03 / Kegiatan Saya</span>
                    <span class="font-mono-code text-[10px]">&rarr;</span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-stone-800 space-y-2">
            <a href="{{ route('landing.home') }}" class="block text-center w-full py-2.5 px-3 border border-stone-700 font-mono-code text-xs font-bold text-stone-300 hover:border-[#C8963E] hover:text-white transition">
                &larr; KE HALAMAN PUBLIK
            </a>
            <form action="{{ session('penduduk_status') === 'Admin' ? route('admin.logout') : route('karyawan.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2.5 px-3 bg-[#C8552B] hover:bg-rose-700 text-white font-mono-code text-xs font-bold uppercase tracking-wider transition cursor-pointer">
                    AKHIRI SESI [LOGOUT]
                </button>
            </form>
        </div>
    </aside>

    <!-- Workspace Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-[#FFFDF9] border-b-2 border-[#12201B] px-6 py-5 flex flex-wrap items-center justify-between gap-4">
            <div>
                <span class="font-mono-code text-[10px] font-bold uppercase tracking-widest text-[#C8552B] block">
                    // LEMBAR KERJA APARATUR SI KUNJUNG PEMDA
                </span>
                <h1 class="font-serif-display text-2xl font-bold text-[#12201B] mt-0.5">@yield('header', 'Meja Kendali')</h1>
                <p class="text-xs text-[#54615B] mt-0.5">@yield('subheader', 'Sistem Informasi Kunjungan Tamu')</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Dark / Light Mode Toggle -->
                <button type="button" onclick="toggleSikunjungTheme()"
                        title="Ubah Mode Gelap / Terang"
                        class="px-3.5 py-2 bg-[#F5F2EB] border-[1.5px] border-[#12201B] font-mono-code text-xs font-bold text-[#12201B] flex items-center gap-2 cursor-pointer hover:bg-[#C8552B] hover:text-white transition">
                    <span id="dash-theme-icon">&#9790;</span>
                    <span id="dash-theme-label">DARK MODE</span>
                </button>
                <div class="px-3.5 py-2 bg-[#F5F2EB] border-[1.5px] border-[#12201B] font-mono-code text-xs font-bold text-[#12201B]">
                    {{ now()->format('d M Y // H:i') }} WIB
                </div>
            </div>
        </header>

        <main class="p-6 flex-1 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-[#E6F4EE] border-2 border-[#12201B] shadow-[4px_4px_0px_#1B6B53] text-[#12201B] text-sm font-semibold flex items-center gap-3">
                    <span class="px-2 py-0.5 bg-[#1B6B53] text-white font-mono-code text-xs font-bold">OK</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-[#FCEAE6] border-2 border-[#12201B] shadow-[4px_4px_0px_#C8552B] text-[#12201B] text-sm font-semibold">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="p-4 bg-[#FCEAE6] border-2 border-[#12201B] shadow-[4px_4px_0px_#C8552B] text-[#12201B] text-sm">
                    <p class="font-mono-code text-xs font-bold uppercase text-[#C8552B] mb-1">// PERIKSA KEMBALI INPUT:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function syncDashThemeUI() {
            const isDark = document.documentElement.classList.contains('dark');
            const icon = document.getElementById('dash-theme-icon');
            const label = document.getElementById('dash-theme-label');
            if (icon && label) {
                icon.innerHTML = isDark ? '&#9728;' : '&#9790;';
                label.textContent = isDark ? 'LIGHT MODE' : 'DARK MODE';
            }
        }

        function toggleSikunjungTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('sikunjung_theme', isDark ? 'dark' : 'light');
            syncDashThemeUI();
        }

        document.addEventListener('DOMContentLoaded', syncDashThemeUI);
    </script>
</body>
</html>
