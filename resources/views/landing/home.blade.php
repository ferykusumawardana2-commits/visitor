@extends('layouts.app')

@section('title', 'Beranda — Si Kunjung Pemda')

@section('content')
<!-- Architectural Editorial Hero -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-14">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
        <!-- Left Manifesto Box -->
        <div class="lg:col-span-7 civic-card p-8 sm:p-10 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-[#12201B] text-[#C8963E] font-mono-code text-[11px] font-bold px-3.5 py-1.5 border-b-2 border-l-2 border-[#12201B]">
                EDISI LAYANAN // {{ date('Y') }}
            </div>

            <div class="space-y-6 pt-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#F5F2EB] border-[1.5px] border-[#12201B] font-mono-code text-xs font-bold text-[#C8552B]">
                    <span>&#9632; PORTAL RESMI SI KUNJUNG PEMDA</span>
                </div>

                <h1 class="font-serif-display text-4xl sm:text-5xl font-bold text-[#12201B] leading-[1.1] tracking-tight">
                    Tata Kelola Audiensi &amp; Kunjungan Tamu Daerah yang <span class="italic underline decoration-[#C8552B] decoration-4 underline-offset-4">Terbuka</span>.
                </h1>

                <p class="text-[#54615B] text-base leading-relaxed max-w-2xl">
                    Periksa jadwal ketersediaan pejabat dan staf aparatur secara langsung, pantau agenda kegiatan dinas yang sedang berlangsung, dan ajukan tiket permohonan kunjungan dengan verifikasi dua tahap (Admin &amp; Aparatur).
                </p>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3 pt-2">
                    @if(session('pemohon_id'))
                        <a href="{{ route('pemohon.kunjungan.index') }}" class="px-6 py-3.5 civic-btn-primary text-xs font-mono-code font-bold uppercase tracking-wider">
                            + Ajukan Tiket Kunjungan &rarr;
                        </a>
                        <a href="{{ route('pemohon.histori') }}" class="px-5 py-3.5 bg-[#F5F2EB] hover:bg-[#EFECE4] text-[#12201B] border-[1.5px] border-[#12201B] text-xs font-mono-code font-bold uppercase tracking-wider transition">
                            Lihat Arsip Histori
                        </a>
                    @else
                        <a href="{{ route('pemohon.register') }}" class="px-6 py-3.5 civic-btn-primary text-xs font-mono-code font-bold uppercase tracking-wider">
                            + Buat Kartu Pemohon &rarr;
                        </a>
                        <a href="{{ route('pemohon.login') }}" class="px-5 py-3.5 bg-[#F5F2EB] hover:bg-[#EFECE4] text-[#12201B] border-[1.5px] border-[#12201B] text-xs font-mono-code font-bold uppercase tracking-wider transition">
                            Login Pemohon
                        </a>
                    @endif
                    <a href="{{ route('landing.jadwal') }}" class="px-5 py-3.5 bg-[#FFFDF9] hover:bg-[#EFECE4] text-[#12201B] border-[1.5px] border-[#12201B] text-xs font-mono-code font-bold uppercase tracking-wider transition">
                        Papan Jadwal
                    </a>
                </div>
            </div>

            <!-- 3-Step Protocol Strip -->
            <div class="mt-10 pt-6 border-t-2 border-dashed border-[#D6CFC0] grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div class="flex items-start gap-2.5">
                    <span class="font-mono-code font-bold text-xs px-2 py-0.5 bg-[#12201B] text-[#C8963E]">01</span>
                    <div>
                        <p class="font-bold text-[#12201B]">Pilih Sesi Aktif</p>
                        <p class="text-[#54615B] text-[11px]">Cek jadwal berstatus Y (Buka).</p>
                    </div>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="font-mono-code font-bold text-xs px-2 py-0.5 bg-[#12201B] text-[#C8963E]">02</span>
                    <div>
                        <p class="font-bold text-[#12201B]">Isi Tujuan Audiensi</p>
                        <p class="text-[#54615B] text-[11px]">Tentukan tanggal &amp; maksud kunjungan.</p>
                    </div>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="font-mono-code font-bold text-xs px-2 py-0.5 bg-[#12201B] text-[#C8963E]">03</span>
                    <div>
                        <p class="font-bold text-[#12201B]">Konfirmasi Resmi</p>
                        <p class="text-[#54615B] text-[11px]">Pantau persetujuan Admin &amp; Karyawan.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Telemetry & Ledger Box -->
        <div class="lg:col-span-5 flex flex-col justify-between gap-6">
            <div class="bg-[#12201B] text-[#FFFDF9] border-2 border-[#12201B] shadow-[5px_5px_0px_#C8552B] p-6 sm:p-7 flex-1 flex flex-col justify-between">
                <div class="flex items-center justify-between border-b border-stone-700 pb-4">
                    <div>
                        <span class="font-mono-code text-[10px] uppercase tracking-widest text-[#C8963E] block">// TELEMETRI LAYANAN</span>
                        <h2 class="font-serif-display text-xl font-bold text-white">Indeks Operasional</h2>
                    </div>
                    <span class="px-2.5 py-1 bg-[#1B6B53] text-white font-mono-code text-[11px] font-bold">
                        LIVE DATA
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 my-6">
                    <div class="p-4 bg-[#192B24] border border-stone-700">
                        <span class="font-mono-code text-[10px] text-[#C8963E] uppercase block">01 // SESI TERBUKA</span>
                        <p class="font-mono-code text-3xl font-bold text-white mt-2">{{ str_pad((string) $stats['total_jadwal_aktif'], 2, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-[11px] text-stone-400 mt-1">Jadwal Kunjungan (Y)</p>
                    </div>
                    <div class="p-4 bg-[#192B24] border border-stone-700">
                        <span class="font-mono-code text-[10px] text-emerald-400 uppercase block">02 // PERSONEL</span>
                        <p class="font-mono-code text-3xl font-bold text-white mt-2">{{ str_pad((string) $stats['total_karyawan'], 2, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-[11px] text-stone-400 mt-1">Aparatur / Karyawan</p>
                    </div>
                    <div class="p-4 bg-[#192B24] border border-stone-700">
                        <span class="font-mono-code text-[10px] text-amber-300 uppercase block">03 // UNIT KERJA</span>
                        <p class="font-mono-code text-3xl font-bold text-white mt-2">{{ str_pad((string) $stats['total_jabatan'], 2, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-[11px] text-stone-400 mt-1">Struktur Jabatan</p>
                    </div>
                    <div class="p-4 bg-[#192B24] border border-stone-700">
                        <span class="font-mono-code text-[10px] text-sky-300 uppercase block">04 // PERMOHONAN</span>
                        <p class="font-mono-code text-3xl font-bold text-white mt-2">{{ str_pad((string) $stats['total_kunjungan'], 2, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-[11px] text-stone-400 mt-1">Total Kunjungan</p>
                    </div>
                </div>

                <div class="pt-3 border-t border-stone-700 flex items-center justify-between text-xs">
                    <span class="font-mono-code text-[11px] text-stone-400">JAM LAYANAN STANDAR: 08:00 - 16:00 WIB</span>
                    <a href="{{ route('landing.struktur') }}" class="font-mono-code text-[11px] text-[#C8963E] hover:underline font-bold">
                        DIREKTORI &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Boarding Pass Style Jadwal Kunjungan Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-6 border-b-2 border-[#12201B]">
        <div>
            <span class="font-mono-code text-xs font-bold uppercase tracking-widest text-[#C8552B]">// 01. KUPON JADWAL AUDIENSI</span>
            <h2 class="font-serif-display text-3xl font-bold text-[#12201B] mt-1">Jadwal Kunjungan Aktif Si Kunjung Pemda</h2>
        </div>
        <a href="{{ route('landing.jadwal') }}" class="px-4 py-2 bg-[#FFFDF9] border-[1.5px] border-[#12201B] shadow-[3px_3px_0px_#12201B] font-mono-code text-xs font-bold uppercase hover:bg-[#12201B] hover:text-white transition">
            Buka Semua Jadwal &rarr;
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
        @forelse($jadwalAktif as $jadwal)
            <div class="civic-card civic-card-hover flex flex-col justify-between">
                <!-- Ticket Top Header -->
                <div class="p-6">
                    <div class="flex items-center justify-between gap-2 pb-3 border-b border-[#D6CFC0]">
                        <span class="font-mono-code text-xs font-bold text-[#54615B]">
                            SESI #{{ str_pad((string) $jadwal->id_settings_kunjungan, 3, '0', STR_PAD_LEFT) }}
                        </span>
                        <span class="px-2.5 py-0.5 bg-[#1B6B53] text-white font-mono-code text-[11px] font-bold uppercase">
                            [STATUS: Y &bull; AKTIF]
                        </span>
                    </div>

                    <div class="mt-4">
                        <p class="font-mono-code text-[11px] font-bold uppercase tracking-wider text-[#C8552B]">
                            {{ $jadwal->jabatanPenduduk?->jabatan?->nama_jabatan ?? '-' }}
                        </p>
                        <h3 class="font-serif-display text-xl font-bold text-[#12201B] mt-1">
                            {{ $jadwal->jabatanPenduduk?->penduduk?->nama_penduduk ?? '-' }}
                        </h3>
                    </div>

                    <div class="mt-5 p-3 bg-[#F5F2EB] border border-[#12201B] grid grid-cols-2 gap-2 font-mono-code text-xs">
                        <div>
                            <span class="text-[10px] text-[#54615B] block">TANGGAL</span>
                            <strong class="text-[#12201B]">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</strong>
                        </div>
                        <div>
                            <span class="text-[10px] text-[#54615B] block">JAM OPERASIONAL</span>
                            <strong class="text-[#12201B]">{{ substr($jadwal->jam_aktif, 0, 5) }} - {{ substr($jadwal->jam_non_aktif, 0, 5) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Perforated Ticket Stub Footer -->
                <div class="ticket-notch border-t-2 border-dashed border-[#12201B] px-6 py-4 bg-[#F5F2EB]/60 flex items-center justify-between">
                    <span class="font-mono-code text-[11px] font-semibold text-[#54615B]">TERSEDIA UNTUK TAMU</span>
                    <a href="{{ session('pemohon_id') ? route('pemohon.kunjungan.index', ['id_settings_kunjungan' => $jadwal->id_settings_kunjungan]) : route('pemohon.login') }}"
                       class="px-3.5 py-1.5 bg-[#12201B] hover:bg-[#C8552B] text-white font-mono-code text-xs font-bold uppercase transition">
                        Ambil Jadwal &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full civic-card p-10 text-center font-mono-code text-sm text-[#54615B]">
                // BELUM ADA JADWAL KUNJUNGAN AKTIF SAAT INI.
            </div>
        @endforelse
    </div>
</section>

<!-- Editorial Spread: Kegiatan & Struktur Karyawan -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Column: Agenda Kegiatan Dinas -->
        <div class="lg:col-span-5 civic-card p-6 sm:p-8">
            <div class="pb-4 border-b-2 border-[#12201B]">
                <span class="font-mono-code text-xs font-bold uppercase tracking-widest text-[#C8552B]">// 02. LOG KEGIATAN</span>
                <h2 class="font-serif-display text-2xl font-bold text-[#12201B] mt-1">Agenda Kegiatan Aparatur</h2>
                <p class="text-xs text-[#54615B] mt-0.5">Jadwal kegiatan dinas pejabat/karyawan sebagai acuan tamu.</p>
            </div>

            <div class="divide-y divide-[#D6CFC0] mt-4">
                @forelse($kegiatanList as $kegiatan)
                    <div class="py-4 space-y-1.5">
                        <div class="flex items-center justify-between font-mono-code text-[11px]">
                            <span class="px-2 py-0.5 bg-[#12201B] text-[#C8963E] font-bold">
                                {{ \Carbon\Carbon::parse($kegiatan->tanggal_awal)->format('d/m/Y') }} &mdash; {{ \Carbon\Carbon::parse($kegiatan->tanggal_akhir)->format('d/m/Y') }}
                            </span>
                            <span class="text-[#C8552B] font-bold truncate max-w-[160px]">
                                {{ $kegiatan->jabatanPenduduk?->jabatan?->nama_jabatan ?? '-' }}
                            </span>
                        </div>
                        <p class="font-bold text-sm text-[#12201B] leading-snug pt-1">{{ $kegiatan->kegiatan }}</p>
                        <p class="font-mono-code text-xs text-[#54615B]">
                            Pelaksana: <strong class="text-[#12201B]">{{ $kegiatan->jabatanPenduduk?->penduduk?->nama_penduduk ?? '-' }}</strong>
                        </p>
                    </div>
                @empty
                    <p class="py-6 font-mono-code text-xs text-[#54615B]">// BELUM ADA AGENDA KEGIATAN TERCATAT.</p>
                @endforelse
            </div>
        </div>

        <!-- Right Column: Struktur Karyawan / Penduduk -->
        <div class="lg:col-span-7 civic-card p-6 sm:p-8 flex flex-col justify-between">
            <div>
                <div class="pb-4 border-b-2 border-[#12201B] flex flex-wrap items-end justify-between gap-2">
                    <div>
                        <span class="font-mono-code text-xs font-bold uppercase tracking-widest text-[#1B6B53]">// 03. DIREKTORI APARATUR</span>
                        <h2 class="font-serif-display text-2xl font-bold text-[#12201B] mt-1">Struktur Karyawan / Penduduk</h2>
                    </div>
                    <a href="{{ route('landing.struktur') }}" class="font-mono-code text-xs font-bold text-[#C8552B] hover:underline">
                        LIHAT DIREKTORI LENGKAP &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                    @forelse($strukturList as $index => $item)
                        <div class="p-4 bg-[#F5F2EB] border-[1.5px] border-[#12201B] flex items-start gap-3.5 relative">
                            <div class="w-11 h-11 bg-[#12201B] text-[#FFFDF9] font-serif-display font-bold text-base flex items-center justify-center flex-shrink-0 border border-[#12201B] shadow-[2px_2px_0px_#C8552B]">
                                {{ strtoupper(substr($item->penduduk?->nama_penduduk ?? 'A', 0, 2)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="font-mono-code text-[10px] font-bold uppercase text-[#C8552B] block truncate">
                                    {{ $item->jabatan?->nama_jabatan ?? '-' }}
                                </span>
                                <h3 class="font-bold text-sm text-[#12201B] truncate mt-0.5">
                                    {{ $item->penduduk?->nama_penduduk ?? '-' }}
                                </h3>
                                <p class="font-mono-code text-[11px] text-[#54615B] mt-1 truncate">
                                    {{ $item->penduduk?->alamat ?? '-' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full font-mono-code text-xs text-[#54615B]">// BELUM ADA DATA STRUKTUR.</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-[#D6CFC0] flex items-center justify-between font-mono-code text-xs text-[#54615B]">
                <span>DATA TERINTEGRASI DENGAN TABEL PENDUDUK &amp; JABATAN</span>
                <span class="text-[#12201B] font-bold">SI KUNJUNG PEMDA</span>
            </div>
        </div>
    </div>
</section>
@endsection
