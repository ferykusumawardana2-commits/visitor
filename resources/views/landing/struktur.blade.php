@extends('layouts.app')

@section('title', 'Struktur Karyawan / Penduduk — Si Kunjung Pemda')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <!-- Dossier Header & Filter -->
    <div class="civic-card p-6 sm:p-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="font-mono-code text-xs font-bold uppercase tracking-widest text-[#C8552B]">// DIREKTORI ORGANISASI &amp; PEJABAT</span>
                <h1 class="font-serif-display text-3xl sm:text-4xl font-bold text-[#12201B] mt-1">Struktur Karyawan / Penduduk Si Kunjung Pemda</h1>
                <p class="text-sm text-[#54615B] mt-1">Kartu direktori pejabat, pimpinan unit, dan aparatur beserta penempatan jabatan serta ketersediaan jadwal audiensi.</p>
            </div>

            <form method="GET" action="{{ route('landing.struktur') }}" class="flex flex-wrap items-center gap-2">
                <select name="id_jabatan" class="bg-[#F5F2EB] border-[1.5px] border-[#12201B] px-3 py-2.5 text-xs font-semibold">
                    <option value="">-- Semua Jabatan --</option>
                    @foreach($jabatanList as $jab)
                        <option value="{{ $jab->id_jabatan }}" @selected((string) request('id_jabatan') === (string) $jab->id_jabatan)>
                            {{ $jab->nama_jabatan }}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama personel..."
                       class="bg-[#F5F2EB] border-[1.5px] border-[#12201B] px-3.5 py-2.5 text-xs">
                <button type="submit" class="px-4 py-2.5 civic-btn-primary font-mono-code text-xs font-bold uppercase cursor-pointer">
                    Cari
                </button>
            </form>
        </div>
    </div>

    <!-- Dossier Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($strukturList as $index => $item)
            <div class="civic-card civic-card-hover flex flex-col justify-between">
                <div>
                    <!-- Card Top Bar -->
                    <div class="px-5 py-3 bg-[#12201B] text-[#FFFDF9] flex items-center justify-between font-mono-code text-[11px]">
                        <span class="text-[#C8963E] font-bold">DOSSIER #{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="px-2 py-0.5 {{ $item->status === 'Y' ? 'bg-[#1B6B53] text-white' : 'bg-[#C8552B] text-white' }} font-bold">
                            STATUS: {{ $item->status }}
                        </span>
                    </div>

                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 bg-[#F5F2EB] border-2 border-[#12201B] shadow-[3px_3px_0px_#C8552B] flex items-center justify-center font-serif-display font-bold text-xl text-[#12201B] flex-shrink-0">
                                {{ strtoupper(substr($item->penduduk?->nama_penduduk ?? 'P', 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <span class="font-mono-code text-[11px] font-bold uppercase text-[#C8552B] block">
                                    {{ $item->jabatan?->nama_jabatan ?? '-' }}
                                </span>
                                <h3 class="font-serif-display text-xl font-bold text-[#12201B] leading-snug mt-0.5">
                                    {{ $item->penduduk?->nama_penduduk ?? '-' }}
                                </h3>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-t-2 border-dashed border-[#D6CFC0] space-y-2 font-mono-code text-xs">
                            <div class="flex justify-between">
                                <span class="text-[#54615B]">OTORITAS:</span>
                                <strong class="text-[#12201B]">{{ $item->penduduk?->status === 'Admin' ? 'ADMINISTRATOR' : 'APARATUR / PENDUDUK' }}</strong>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#54615B]">GENDER:</span>
                                <strong class="text-[#12201B]">{{ $item->penduduk?->jenis_kelamin === 'L' ? 'LAKI-LAKI [L]' : 'PEREMPUAN [P]' }}</strong>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#54615B]">SESI AKTIF:</span>
                                <strong class="text-[#1B6B53]">{{ $item->settingsKunjungan->where('status', 'Y')->count() }} JADWAL</strong>
                            </div>
                            <div class="pt-1 text-[11px] text-[#54615B] truncate">
                                DOMISILI: {{ $item->penduduk?->alamat ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-3.5 bg-[#EFECE4] border-t-[1.5px] border-[#12201B] flex items-center justify-between">
                    <span class="font-mono-code text-[10px] font-bold text-[#54615B]">ID JABATAN: #{{ $item->id_jabatan }}</span>
                    <a href="{{ route('landing.jadwal', ['id_jabatan' => $item->id_jabatan]) }}"
                       class="font-mono-code text-xs font-bold text-[#12201B] hover:text-[#C8552B] underline">
                        LIHAT JADWAL &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full civic-card p-10 text-center font-mono-code text-xs text-[#54615B]">
                // TIDAK ADA DATA STRUKTUR KARYAWAN / PENDUDUK YANG DITEMUKAN.
            </div>
        @endforelse
    </div>
</div>
@endsection
