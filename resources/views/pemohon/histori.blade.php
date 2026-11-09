@extends('layouts.app')

@section('title', 'Histori Kunjungan — Si Kunjung Pemda')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    <!-- Header & Filter Tabs -->
    <div class="civic-card p-6 sm:p-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <span class="font-mono-code text-xs font-bold uppercase tracking-widest text-[#C8552B]">// ARSIP BUKU TAMU</span>
            <h1 class="font-serif-display text-3xl font-bold text-[#12201B] mt-1">Histori Kunjungan Saya</h1>
            <p class="text-sm text-[#54615B] mt-1">Rekam jejak seluruh permohonan kunjungan, waktu verifikasi, serta catatan Admin dan Karyawan Si Kunjung Pemda.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 font-mono-code text-xs">
            <a href="{{ route('pemohon.histori', ['status' => 'semua']) }}"
               class="px-3.5 py-2 border-[1.5px] border-[#12201B] font-bold {{ $filterStatus === 'semua' ? 'bg-[#12201B] text-white' : 'bg-[#F5F2EB] text-[#12201B]' }}">
                SEMUA
            </a>
            <a href="{{ route('pemohon.histori', ['status' => 'disetujui']) }}"
               class="px-3.5 py-2 border-[1.5px] border-[#12201B] font-bold {{ $filterStatus === 'disetujui' ? 'bg-[#1B6B53] text-white' : 'bg-[#F5F2EB] text-[#12201B]' }}">
                DISETUJUI [Y]
            </a>
            <a href="{{ route('pemohon.histori', ['status' => 'ditolak']) }}"
               class="px-3.5 py-2 border-[1.5px] border-[#12201B] font-bold {{ $filterStatus === 'ditolak' ? 'bg-[#C8552B] text-white' : 'bg-[#F5F2EB] text-[#12201B]' }}">
                DITOLAK [T]
            </a>
            <a href="{{ route('pemohon.histori', ['status' => 'menunggu']) }}"
               class="px-3.5 py-2 border-[1.5px] border-[#12201B] font-bold {{ $filterStatus === 'menunggu' ? 'bg-[#C8963E] text-[#12201B]' : 'bg-[#F5F2EB] text-[#12201B]' }}">
                MENUNGGU
            </a>
        </div>
    </div>

    <!-- Archive Table -->
    <div class="civic-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-[#12201B] text-[#FFFDF9] font-mono-code text-[11px] uppercase">
                        <th class="py-3.5 px-4 font-bold">Tiket</th>
                        <th class="py-3.5 px-4 font-bold">Pejabat / Karyawan Tujuan</th>
                        <th class="py-3.5 px-4 font-bold">Waktu Kunjungan</th>
                        <th class="py-3.5 px-4 font-bold">Maksud / Tujuan</th>
                        <th class="py-3.5 px-4 font-bold">Verifikasi Admin</th>
                        <th class="py-3.5 px-4 font-bold">Verifikasi Karyawan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D6CFC0]">
                    @forelse($historiList as $item)
                        <tr class="hover:bg-[#F5F2EB] align-top">
                            <td class="py-4 px-4 font-mono-code text-xs font-bold text-[#54615B]">#{{ str_pad((string) $item->id_kunjungan, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-4 px-4">
                                <p class="font-bold text-[#12201B]">{{ $item->settingsKunjungan?->jabatanPenduduk?->penduduk?->nama_penduduk ?? '-' }}</p>
                                <p class="font-mono-code text-xs font-bold text-[#C8552B]">{{ $item->settingsKunjungan?->jabatanPenduduk?->jabatan?->nama_jabatan ?? '-' }}</p>
                            </td>
                            <td class="py-4 px-4 font-mono-code text-xs">
                                <p class="font-bold text-[#12201B]">{{ \Carbon\Carbon::parse($item->tanggal_waktu)->format('d M Y, H:i') }}</p>
                                @if($item->settingsKunjungan)
                                    <p class="text-[#54615B] text-[11px] mt-0.5">
                                        Sesi: {{ substr($item->settingsKunjungan->jam_aktif, 0, 5) }}-{{ substr($item->settingsKunjungan->jam_non_aktif, 0, 5) }}
                                    </p>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-[#12201B] max-w-xs">{{ $item->tujuannya }}</td>
                            <td class="py-4 px-4 text-xs">
                                @if($item->konfrimasi_admin === 'Y')
                                    <span class="inline-block px-2 py-0.5 bg-[#E6F4EE] text-[#1B6B53] border border-[#1B6B53] font-mono-code font-bold">[Y] DISETUJUI</span>
                                @elseif($item->konfrimasi_admin === 'T')
                                    <span class="inline-block px-2 py-0.5 bg-[#FCEAE6] text-[#C8552B] border border-[#C8552B] font-mono-code font-bold">[T] DITOLAK</span>
                                @else
                                    <span class="inline-block px-2 py-0.5 bg-[#FFF8E6] text-[#9A6B1F] border border-[#C8963E] font-mono-code font-bold">MENUNGGU</span>
                                @endif
                                @if($item->catatan_admin)
                                    <p class="mt-1.5 p-2 bg-[#F5F2EB] border border-[#12201B] text-[#12201B]">
                                        <strong>Catatan:</strong> {{ $item->catatan_admin }}
                                    </p>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-xs">
                                @if($item->konfrimasi_penduduk === 'Y')
                                    <span class="inline-block px-2 py-0.5 bg-[#E6F4EE] text-[#1B6B53] border border-[#1B6B53] font-mono-code font-bold">[Y] DISETUJUI</span>
                                @elseif($item->konfrimasi_penduduk === 'T')
                                    <span class="inline-block px-2 py-0.5 bg-[#FCEAE6] text-[#C8552B] border border-[#C8552B] font-mono-code font-bold">[T] DITOLAK</span>
                                @else
                                    <span class="inline-block px-2 py-0.5 bg-[#FFF8E6] text-[#9A6B1F] border border-[#C8963E] font-mono-code font-bold">MENUNGGU</span>
                                @endif
                                @if($item->catatan_penduduk)
                                    <p class="mt-1.5 p-2 bg-[#F5F2EB] border border-[#12201B] text-[#12201B]">
                                        <strong>Catatan:</strong> {{ $item->catatan_penduduk }}
                                    </p>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center font-mono-code text-xs text-[#54615B]">// BELUM ADA RIWAYAT KUNJUNGAN.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
