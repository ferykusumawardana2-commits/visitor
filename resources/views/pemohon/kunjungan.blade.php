@extends('layouts.app')

@section('title', 'Pengajuan Kunjungan — Si Kunjung Pemda')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <!-- Visitor Identity Header -->
    <div class="civic-card p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="font-mono-code text-xs font-bold uppercase tracking-widest text-[#C8552B]">// KARTU PEMOHON AKTIF</span>
            <h1 class="font-serif-display text-3xl font-bold text-[#12201B] mt-1">Manajemen Kunjungan Tamu (CRUD)</h1>
            <p class="font-mono-code text-xs text-[#54615B] mt-1">
                PEMOHON: <strong class="text-[#12201B]">{{ strtoupper($pemohon->nama) }}</strong> // {{ $pemohon->email }} // {{ $pemohon->telepon }}
            </p>
        </div>
        <a href="{{ route('pemohon.histori') }}" class="px-5 py-3 civic-btn-primary font-mono-code text-xs font-bold uppercase tracking-wider">
            Buka Arsip Histori Kunjungan &rarr;
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Left: Create Kunjungan Ticket Form -->
        <div class="lg:col-span-4 civic-card overflow-hidden">
            <div class="px-6 py-4 bg-[#12201B] text-[#FFFDF9] flex items-center justify-between">
                <div>
                    <span class="font-mono-code text-[10px] text-[#C8963E] uppercase block">// FORMULIR PENGAJUAN</span>
                    <h2 class="font-serif-display text-lg font-bold">Terbitkan Tiket Kunjungan</h2>
                </div>
                <span class="px-2 py-0.5 bg-[#C8552B] text-white font-mono-code text-[10px] font-bold">CREATE</span>
            </div>

            <form method="POST" action="{{ route('pemohon.kunjungan.store') }}" class="p-6 space-y-4 bg-[#FFFDF9]">
                @csrf
                <div>
                    <label class="block font-mono-code text-[11px] font-bold uppercase text-[#12201B] mb-1.5">01 / Pilih Sesi Jadwal Aktif (Y)</label>
                    <select name="id_settings_kunjungan" required
                            class="w-full bg-[#F5F2EB] border-[1.5px] border-[#12201B] px-3 py-2.5 text-xs font-semibold focus:outline-none focus:bg-white">
                        <option value="">-- Pilih Pejabat / Karyawan &amp; Tanggal --</option>
                        @foreach($jadwalTersedia as $jadwal)
                            <option value="{{ $jadwal->id_settings_kunjungan }}"
                                @selected((string) old('id_settings_kunjungan', $selectedJadwalId) === (string) $jadwal->id_settings_kunjungan)>
                                {{ $jadwal->jabatanPenduduk?->penduduk?->nama_penduduk ?? '-' }}
                                ({{ $jadwal->jabatanPenduduk?->jabatan?->nama_jabatan ?? '-' }})
                                — {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                                [{{ substr($jadwal->jam_aktif, 0, 5) }}-{{ substr($jadwal->jam_non_aktif, 0, 5) }}]
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-mono-code text-[11px] font-bold uppercase text-[#12201B] mb-1.5">02 / Tanggal &amp; Waktu Kedatangan</label>
                    <input type="datetime-local" name="tanggal_waktu"
                           value="{{ old('tanggal_waktu', now()->addDay()->setTime(9, 0)->format('Y-m-d\TH:i')) }}"
                           required
                           class="w-full bg-[#F5F2EB] border-[1.5px] border-[#12201B] px-3.5 py-2.5 text-xs font-mono-code focus:outline-none focus:bg-white">
                </div>

                <div>
                    <label class="block font-mono-code text-[11px] font-bold uppercase text-[#12201B] mb-1.5">03 / Maksud &amp; Tujuan Kunjungan</label>
                    <textarea name="tujuannya" rows="3" required maxlength="255"
                              placeholder="Uraikan keperluan audiensi atau kunjungan Anda..."
                              class="w-full bg-[#F5F2EB] border-[1.5px] border-[#12201B] px-3.5 py-2.5 text-sm focus:outline-none focus:bg-white">{{ old('tujuannya') }}</textarea>
                </div>

                <button type="submit" class="w-full py-3.5 civic-btn-terra font-mono-code text-xs font-bold uppercase tracking-widest cursor-pointer">
                    + Kirim Permohonan Kunjungan
                </button>
            </form>
        </div>

        <!-- Right: Active Tickets List (Read, Update, Delete) -->
        <div class="lg:col-span-8 space-y-5">
            <div class="civic-card overflow-hidden">
                <div class="px-6 py-4 bg-[#EFECE4] border-b-2 border-[#12201B] flex items-center justify-between">
                    <h2 class="font-serif-display text-xl font-bold text-[#12201B]">Daftar Tiket Permohonan Kunjungan Saya</h2>
                    <span class="font-mono-code text-xs font-bold text-[#C8552B]">{{ $kunjunganList->count() }} TIKET</span>
                </div>

                <div class="divide-y-2 divide-[#12201B]">
                    @forelse($kunjunganList as $item)
                        <div class="p-6 bg-[#FFFDF9] space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2 font-mono-code text-xs">
                                        <span class="px-2 py-0.5 bg-[#12201B] text-[#C8963E] font-bold">
                                            TIKET #{{ str_pad((string) $item->id_kunjungan, 3, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span class="text-[#C8552B] font-bold uppercase">
                                            {{ $item->settingsKunjungan?->jabatanPenduduk?->jabatan?->nama_jabatan ?? '-' }}
                                        </span>
                                        <span class="text-[#54615B]">// {{ \Carbon\Carbon::parse($item->tanggal_waktu)->format('d M Y, H:i') }} WIB</span>
                                    </div>

                                    <h3 class="font-serif-display text-xl font-bold text-[#12201B] mt-2">
                                        Pejabat/Karyawan: {{ $item->settingsKunjungan?->jabatanPenduduk?->penduduk?->nama_penduduk ?? '-' }}
                                    </h3>
                                    <p class="text-sm text-[#12201B] mt-1 bg-[#F5F2EB] p-3 border-l-4 border-[#12201B]">
                                        <span class="font-mono-code text-[11px] font-bold uppercase text-[#54615B] block">KEPERLUAN KUNJUNGAN:</span>
                                        {{ $item->tujuannya }}
                                    </p>
                                </div>

                                <!-- Stamp Badges -->
                                <div class="flex flex-col gap-1.5 font-mono-code text-[11px] flex-shrink-0">
                                    @if($item->konfrimasi_admin === 'Y')
                                        <span class="px-2.5 py-1 bg-[#E6F4EE] text-[#1B6B53] border border-[#1B6B53] font-bold">
                                            ADMIN: [Y] DISETUJUI
                                        </span>
                                    @elseif($item->konfrimasi_admin === 'T')
                                        <span class="px-2.5 py-1 bg-[#FCEAE6] text-[#C8552B] border border-[#C8552B] font-bold">
                                            ADMIN: [T] DITOLAK
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-[#FFF8E6] text-[#9A6B1F] border border-[#C8963E] font-bold">
                                            ADMIN: MENUNGGU
                                        </span>
                                    @endif

                                    @if($item->konfrimasi_penduduk === 'Y')
                                        <span class="px-2.5 py-1 bg-[#E6F4EE] text-[#1B6B53] border border-[#1B6B53] font-bold">
                                            APARATUR: [Y] DISETUJUI
                                        </span>
                                    @elseif($item->konfrimasi_penduduk === 'T')
                                        <span class="px-2.5 py-1 bg-[#FCEAE6] text-[#C8552B] border border-[#C8552B] font-bold">
                                            APARATUR: [T] DITOLAK
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-[#FFF8E6] text-[#9A6B1F] border border-[#C8963E] font-bold">
                                            APARATUR: MENUNGGU
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Catatan Admin / Karyawan -->
                            @if($item->catatan_admin || $item->catatan_penduduk)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                    @if($item->catatan_admin)
                                        <div class="p-3 border-[1.5px] border-[#12201B] {{ $item->konfrimasi_admin === 'T' ? 'bg-[#FCEAE6]' : 'bg-[#F5F2EB]' }}">
                                            <p class="font-mono-code text-[10px] font-bold uppercase text-[#C8552B]">CATATAN ADMIN ({{ $item->konfrimasi_admin ?? '-' }}):</p>
                                            <p class="font-medium text-[#12201B] mt-0.5">{{ $item->catatan_admin }}</p>
                                        </div>
                                    @endif
                                    @if($item->catatan_penduduk)
                                        <div class="p-3 border-[1.5px] border-[#12201B] {{ $item->konfrimasi_penduduk === 'T' ? 'bg-[#FCEAE6]' : 'bg-[#F5F2EB]' }}">
                                            <p class="font-mono-code text-[10px] font-bold uppercase text-[#1B6B53]">CATATAN KARYAWAN ({{ $item->konfrimasi_penduduk ?? '-' }}):</p>
                                            <p class="font-medium text-[#12201B] mt-0.5">{{ $item->catatan_penduduk }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Edit / Delete Drawer -->
                            <details class="group bg-[#F5F2EB] border-[1.5px] border-[#12201B] p-3.5">
                                <summary class="cursor-pointer font-mono-code text-xs font-bold text-[#12201B] flex items-center justify-between">
                                    <span>[UBAH / BATALKAN PERMOHONAN KUNJUNGAN #{{ $item->id_kunjungan }}]</span>
                                    <span class="text-[#C8552B] group-open:rotate-180 transition">&#9660;</span>
                                </summary>
                                <div class="mt-4 pt-3 border-t border-[#12201B] space-y-3">
                                    <form method="POST" action="{{ route('pemohon.kunjungan.update', $item->id_kunjungan) }}" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @csrf
                                        @method('PUT')
                                        <div class="sm:col-span-2">
                                            <label class="block font-mono-code text-[10px] font-bold uppercase mb-1">Jadwal Kunjungan</label>
                                            <select name="id_settings_kunjungan" required class="w-full bg-white border border-[#12201B] px-3 py-2 text-xs">
                                                @foreach($jadwalTersedia as $jadwal)
                                                    <option value="{{ $jadwal->id_settings_kunjungan }}"
                                                        @selected($item->id_settings_kunjungan === $jadwal->id_settings_kunjungan)>
                                                        {{ $jadwal->jabatanPenduduk?->penduduk?->nama_penduduk ?? '-' }}
                                                        ({{ $jadwal->jabatanPenduduk?->jabatan?->nama_jabatan ?? '-' }})
                                                        — {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block font-mono-code text-[10px] font-bold uppercase mb-1">Tanggal &amp; Waktu</label>
                                            <input type="datetime-local" name="tanggal_waktu"
                                                   value="{{ \Carbon\Carbon::parse($item->tanggal_waktu)->format('Y-m-d\TH:i') }}"
                                                   required class="w-full bg-white border border-[#12201B] px-3 py-2 text-xs font-mono-code">
                                        </div>
                                        <div>
                                            <label class="block font-mono-code text-[10px] font-bold uppercase mb-1">Tujuan Kunjungan</label>
                                            <input type="text" name="tujuannya" value="{{ $item->tujuannya }}" required maxlength="255"
                                                   class="w-full bg-white border border-[#12201B] px-3 py-2 text-xs">
                                        </div>
                                        <div class="sm:col-span-2 pt-1">
                                            <button type="submit" class="px-4 py-2 bg-[#12201B] hover:bg-[#1B6B53] text-white font-mono-code text-xs font-bold uppercase cursor-pointer">
                                                Simpan Perubahan [UPDATE]
                                            </button>
                                        </div>
                                    </form>

                                    <form method="POST" action="{{ route('pemohon.kunjungan.destroy', $item->id_kunjungan) }}" class="pt-2 border-t border-[#D6CFC0] flex justify-end">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus pengajuan kunjungan ini?')"
                                                class="px-3.5 py-1.5 bg-[#C8552B] hover:bg-rose-800 text-white font-mono-code text-xs font-bold uppercase cursor-pointer">
                                            Hapus Tiket [DELETE]
                                        </button>
                                    </form>
                                </div>
                            </details>
                        </div>
                    @empty
                        <div class="p-10 text-center font-mono-code text-xs text-[#54615B]">
                            // BELUM ADA PERMOHONAN KUNJUNGAN YANG DIAJUKAN.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
