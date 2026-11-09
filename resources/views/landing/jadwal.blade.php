@extends('layouts.app')

@section('title', 'Jadwal Kunjungan — Si Kunjung Pemda')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
    <!-- Filter Console -->
    <div class="civic-card p-6 sm:p-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-6 border-b-2 border-[#12201B]">
            <div>
                <span class="font-mono-code text-xs font-bold uppercase tracking-widest text-[#C8552B]">// PAPAN INFORMASI LAYANAN</span>
                <h1 class="font-serif-display text-3xl sm:text-4xl font-bold text-[#12201B] mt-1">Jadwal Kunjungan &amp; Kegiatan</h1>
                <p class="text-sm text-[#54615B] mt-1">Daftar waktu aktif penerimaan kunjungan tamu beserta status ketersediaan pejabat/karyawan Si Kunjung Pemda.</p>
            </div>
            <div>
                <a href="{{ session('pemohon_id') ? route('pemohon.kunjungan.index') : route('pemohon.login') }}"
                   class="inline-flex items-center px-5 py-3 civic-btn-terra font-mono-code text-xs font-bold uppercase tracking-wider">
                    + Ajukan Permohonan Kunjungan
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('landing.jadwal') }}" class="mt-6 grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <label class="block font-mono-code text-[11px] font-bold uppercase text-[#12201B] mb-1.5">01 / Tanggal Kunjungan</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                       class="w-full bg-[#F5F2EB] border-[1.5px] border-[#12201B] px-3.5 py-2.5 text-xs font-mono-code focus:outline-none focus:bg-white">
            </div>
            <div>
                <label class="block font-mono-code text-[11px] font-bold uppercase text-[#12201B] mb-1.5">02 / Unit Jabatan</label>
                <select name="id_jabatan" class="w-full bg-[#F5F2EB] border-[1.5px] border-[#12201B] px-3.5 py-2.5 text-xs font-semibold focus:outline-none focus:bg-white">
                    <option value="">-- Semua Jabatan --</option>
                    @foreach($jabatanList as $jab)
                        <option value="{{ $jab->id_jabatan }}" @selected((string) request('id_jabatan') === (string) $jab->id_jabatan)>
                            {{ $jab->nama_jabatan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-mono-code text-[11px] font-bold uppercase text-[#12201B] mb-1.5">03 / Status Sesi</label>
                <select name="status" class="w-full bg-[#F5F2EB] border-[1.5px] border-[#12201B] px-3.5 py-2.5 text-xs font-semibold focus:outline-none focus:bg-white">
                    <option value="">-- Semua Status (Y &amp; T) --</option>
                    <option value="Y" @selected(request('status') === 'Y')>Y — Aktif / Dibuka</option>
                    <option value="T" @selected(request('status') === 'T')>T — Non-Aktif / Ditutup</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2.5 px-4 civic-btn-primary font-mono-code text-xs font-bold uppercase cursor-pointer">
                    Terapkan Filter
                </button>
                <a href="{{ route('landing.jadwal') }}" class="py-2.5 px-3.5 bg-[#F5F2EB] border-[1.5px] border-[#12201B] font-mono-code text-xs font-bold hover:bg-[#D6CFC0] transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Departure Board Table: Setting Kunjungan -->
    <div class="civic-card overflow-hidden">
        <div class="px-6 py-4 bg-[#12201B] text-[#FFFDF9] flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 bg-[#C8552B] inline-block"></span>
                <h2 class="font-serif-display text-lg font-bold">Papan Jadwal Kunjungan Aparatur</h2>
            </div>
            <span class="font-mono-code text-xs text-[#C8963E]">TOTAL TERDAFTAR: {{ $jadwalList->count() }} SESI</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-[#EFECE4] text-[#12201B] font-mono-code text-[11px] uppercase border-b-2 border-[#12201B]">
                        <th class="py-3.5 px-4 font-bold">Kode</th>
                        <th class="py-3.5 px-4 font-bold">Nama Karyawan / Pejabat</th>
                        <th class="py-3.5 px-4 font-bold">Jabatan</th>
                        <th class="py-3.5 px-4 font-bold">Tanggal</th>
                        <th class="py-3.5 px-4 font-bold">Jam Aktif</th>
                        <th class="py-3.5 px-4 font-bold">Jam Non-Aktif</th>
                        <th class="py-3.5 px-4 font-bold">Status</th>
                        <th class="py-3.5 px-4 font-bold text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D6CFC0]">
                    @forelse($jadwalList as $index => $jadwal)
                        <tr class="hover:bg-[#F5F2EB] transition">
                            <td class="py-4 px-4 font-mono-code text-xs font-bold text-[#54615B]">
                                #{{ str_pad((string) $jadwal->id_settings_kunjungan, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="py-4 px-4 font-bold text-[#12201B]">
                                {{ $jadwal->jabatanPenduduk?->penduduk?->nama_penduduk ?? '-' }}
                            </td>
                            <td class="py-4 px-4 font-mono-code text-xs font-bold text-[#C8552B]">
                                {{ $jadwal->jabatanPenduduk?->jabatan?->nama_jabatan ?? '-' }}
                            </td>
                            <td class="py-4 px-4 font-mono-code text-xs font-semibold">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                            </td>
                            <td class="py-4 px-4 font-mono-code text-xs">{{ substr($jadwal->jam_aktif, 0, 5) }} WIB</td>
                            <td class="py-4 px-4 font-mono-code text-xs">{{ substr($jadwal->jam_non_aktif, 0, 5) }} WIB</td>
                            <td class="py-4 px-4">
                                @if($jadwal->status === 'Y')
                                    <span class="px-2.5 py-1 bg-[#E6F4EE] text-[#1B6B53] border border-[#1B6B53] font-mono-code text-xs font-bold">
                                        [Y] AKTIF
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-[#FCEAE6] text-[#C8552B] border border-[#C8552B] font-mono-code text-xs font-bold">
                                        [T] TUTUP
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-right">
                                @if($jadwal->status === 'Y')
                                    <a href="{{ session('pemohon_id') ? route('pemohon.kunjungan.index', ['id_settings_kunjungan' => $jadwal->id_settings_kunjungan]) : route('pemohon.login') }}"
                                       class="inline-block px-3.5 py-1.5 bg-[#12201B] hover:bg-[#C8552B] text-white font-mono-code text-xs font-bold uppercase transition">
                                        Pilih Sesi &rarr;
                                    </a>
                                @else
                                    <span class="font-mono-code text-[11px] text-[#54615B]">NON-AKTIF</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center font-mono-code text-xs text-[#54615B]">
                                // TIDAK ADA JADWAL KUNJUNGAN YANG SESUAI FILTER.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Kegiatan Table -->
    <div class="civic-card overflow-hidden">
        <div class="px-6 py-4 bg-[#EFECE4] border-b-2 border-[#12201B] flex items-center justify-between">
            <div>
                <h2 class="font-serif-display text-lg font-bold text-[#12201B]">Log Kegiatan &amp; Agenda Dinas Aparatur</h2>
                <p class="text-xs text-[#54615B]">Informasi kegiatan resmi yang sedang atau akan dilaksanakan oleh pejabat/karyawan.</p>
            </div>
            <span class="font-mono-code text-xs font-bold text-[#C8552B]">AGENDA DINAS</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-[#F5F2EB] text-[#12201B] font-mono-code text-[11px] uppercase border-b border-[#12201B]">
                        <th class="py-3 px-4 font-bold">Pelaksana / Karyawan</th>
                        <th class="py-3 px-4 font-bold">Jabatan</th>
                        <th class="py-3 px-4 font-bold">Tanggal Awal</th>
                        <th class="py-3 px-4 font-bold">Tanggal Akhir</th>
                        <th class="py-3 px-4 font-bold">Deskripsi Kegiatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D6CFC0]">
                    @forelse($kegiatanList as $kegiatan)
                        <tr class="hover:bg-[#F5F2EB]">
                            <td class="py-3.5 px-4 font-bold text-[#12201B]">
                                {{ $kegiatan->jabatanPenduduk?->penduduk?->nama_penduduk ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-mono-code text-xs font-bold text-[#C8552B]">
                                {{ $kegiatan->jabatanPenduduk?->jabatan?->nama_jabatan ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-mono-code text-xs">{{ \Carbon\Carbon::parse($kegiatan->tanggal_awal)->format('d M Y') }}</td>
                            <td class="py-3.5 px-4 font-mono-code text-xs">{{ \Carbon\Carbon::parse($kegiatan->tanggal_akhir)->format('d M Y') }}</td>
                            <td class="py-3.5 px-4 font-medium text-[#12201B]">{{ $kegiatan->kegiatan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center font-mono-code text-xs text-[#54615B]">// BELUM ADA AGENDA KEGIATAN.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
