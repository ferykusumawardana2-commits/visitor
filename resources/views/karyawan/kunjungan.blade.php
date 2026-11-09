@extends('layouts.dashboard')

@section('title', 'Kunjungan Masuk (Karyawan)')
@section('header', 'Kunjungan Tamu — Portal Karyawan Si Kunjung Pemda')
@section('subheader', 'Konfirmasi pengajuan kunjungan kepada Anda: ubah status (Y/T) dan isi catatan ditolak (T).')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
        <div>
            <h2 class="text-base font-extrabold text-slate-900">Daftar Kunjungan untuk {{ $karyawan->nama_penduduk }}</h2>
            <p class="text-xs text-slate-500">Berikan konfirmasi penerimaan (Y) atau penolakan beserta alasan/catatan ditolak (T).</p>
        </div>
        <span class="text-xs font-bold text-slate-500">{{ $kunjunganList->count() }} permohonan</span>
    </div>

    <div class="divide-y divide-slate-200">
        @forelse($kunjunganList as $item)
            <div class="p-6 grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- Info Kunjungan -->
                <div class="lg:col-span-7 space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-800 text-xs font-extrabold">#{{ $item->id_kunjungan }}</span>
                        <span class="text-xs font-bold text-sky-600">
                            Jadwal Kedatangan: {{ \Carbon\Carbon::parse($item->tanggal_waktu)->format('d M Y, H:i') }} WIB
                        </span>
                        <span class="text-xs text-slate-400">|</span>
                        <span class="text-xs font-semibold text-slate-600">
                            Jabatan: {{ $item->settingsKunjungan?->jabatanPenduduk?->jabatan?->nama_jabatan ?? '-' }}
                        </span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-1">
                        <p class="font-bold text-slate-900 text-sm">Pemohon: {{ $item->pemohon?->nama ?? 'Pemohon Umum' }}</p>
                        <p class="text-slate-600">Kontak: {{ $item->pemohon?->email ?? '-' }} &bull; {{ $item->pemohon?->telepon ?? '-' }}</p>
                        <p class="text-slate-800 pt-1"><strong>Tujuan Kunjungan:</strong> {{ $item->tujuannya }}</p>
                    </div>

                    <div class="text-xs text-slate-600">
                        Status Verifikasi Admin:
                        @if($item->konfrimasi_admin === 'Y')
                            <span class="font-bold text-emerald-700">Disetujui Admin (Y)</span>
                        @elseif($item->konfrimasi_admin === 'T')
                            <span class="font-bold text-rose-700">Ditolak Admin (T) — {{ $item->catatan_admin }}</span>
                        @else
                            <span class="font-bold text-amber-600">Menunggu Admin</span>
                        @endif
                    </div>
                </div>

                <!-- Form Konfirmasi Karyawan (Status Y/T & Catatan Ditolak) -->
                <div class="lg:col-span-5 bg-slate-50 rounded-xl border border-slate-200 p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-slate-800">Konfirmasi Karyawan (Y / T)</span>
                        @if($item->konfrimasi_penduduk === 'Y')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">Disetujui (Y)</span>
                        @elseif($item->konfrimasi_penduduk === 'T')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800">Ditolak (T)</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">Menunggu</span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('karyawan.kunjungan.update', $item->id_kunjungan) }}" class="space-y-3">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status Kunjungan (Y / T)</label>
                            <select name="status" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold bg-white">
                                <option value="Y" @selected($item->konfrimasi_penduduk === 'Y')>Y — Terima Kunjungan</option>
                                <option value="T" @selected($item->konfrimasi_penduduk === 'T')>T — Tolak Kunjungan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Ditolak (T) / Pesan Karyawan</label>
                            <input type="text" name="catatan_penduduk" value="{{ $item->catatan_penduduk }}" maxlength="255"
                                   placeholder="Wajib diisi bila status ditolak (T)..."
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs bg-white">
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="submit" class="px-4 py-2 rounded-lg bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold cursor-pointer">
                                Simpan Konfirmasi
                            </button>
                            @if($item->tanggal_waktu_penduduk)
                                <span class="text-[11px] text-slate-400">{{ \Carbon\Carbon::parse($item->tanggal_waktu_penduduk)->format('d/m/Y H:i') }}</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-sm text-slate-500">
                Belum ada pengajuan kunjungan untuk jadwal Anda.
            </div>
        @endforelse
    </div>
</div>
@endsection
