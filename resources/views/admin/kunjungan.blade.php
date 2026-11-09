@extends('layouts.dashboard')

@section('title', 'Pengajuan Kunjungan (Admin)')
@section('header', 'Pengajuan Kunjungan — Admin Si Kunjung Pemda')
@section('subheader', 'Verifikasi pengajuan kunjungan pemohon: ubah status (Y/T) dan berikan catatan ditolak (T).')

@section('content')
<!-- Stats Summary -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <p class="text-xs font-bold uppercase text-slate-400">Total Pengajuan</p>
        <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['total_kunjungan'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <p class="text-xs font-bold uppercase text-amber-600">Menunggu Konfirmasi</p>
        <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $stats['menunggu_admin'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <p class="text-xs font-bold uppercase text-emerald-600">Disetujui Admin (Y)</p>
        <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $stats['disetujui_admin'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <p class="text-xs font-bold uppercase text-rose-600">Ditolak Admin (T)</p>
        <p class="text-2xl font-extrabold text-rose-600 mt-1">{{ $stats['ditolak_admin'] }}</p>
    </div>
</div>

<!-- Filter & Table -->
<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
    <div class="px-6 py-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-base font-extrabold text-slate-900">Daftar Pengajuan Kunjungan Masuk</h2>
        <div class="flex items-center gap-2 text-xs">
            <a href="{{ route('admin.kunjungan.index') }}"
               class="px-3 py-1.5 rounded-lg font-bold {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700' }}">Semua</a>
            <a href="{{ route('admin.kunjungan.index', ['status' => 'PENDING']) }}"
               class="px-3 py-1.5 rounded-lg font-bold {{ request('status') === 'PENDING' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-700' }}">Menunggu</a>
            <a href="{{ route('admin.kunjungan.index', ['status' => 'Y']) }}"
               class="px-3 py-1.5 rounded-lg font-bold {{ request('status') === 'Y' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700' }}">Disetujui (Y)</a>
            <a href="{{ route('admin.kunjungan.index', ['status' => 'T']) }}"
               class="px-3 py-1.5 rounded-lg font-bold {{ request('status') === 'T' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-700' }}">Ditolak (T)</a>
        </div>
    </div>

    <div class="divide-y divide-slate-200">
        @forelse($kunjunganList as $item)
            <div class="p-6 grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- Detail Pengajuan -->
                <div class="lg:col-span-7 space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-800 text-xs font-extrabold">#{{ $item->id_kunjungan }}</span>
                        <span class="text-xs font-bold text-indigo-600">
                            Waktu Kunjungan: {{ \Carbon\Carbon::parse($item->tanggal_waktu)->format('d M Y, H:i') }} WIB
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1 text-xs">
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                            <p class="text-slate-400 font-semibold uppercase text-[10px]">Data Pemohon</p>
                            <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $item->pemohon?->nama ?? 'Pemohon Umum' }}</p>
                            <p class="text-slate-600">{{ $item->pemohon?->email ?? '-' }} &bull; {{ $item->pemohon?->telepon ?? '-' }}</p>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                            <p class="text-slate-400 font-semibold uppercase text-[10px]">Tujuan Pejabat / Karyawan</p>
                            <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $item->settingsKunjungan?->jabatanPenduduk?->penduduk?->nama_penduduk ?? '-' }}</p>
                            <p class="text-indigo-600 font-semibold">{{ $item->settingsKunjungan?->jabatanPenduduk?->jabatan?->nama_jabatan ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="text-xs text-slate-700 pt-1">
                        <span class="font-bold text-slate-900">Tujuan / Keperluan Kunjungan:</span>
                        {{ $item->tujuannya }}
                    </div>

                    <div class="flex flex-wrap items-center gap-3 pt-1 text-xs">
                        <span>Status Karyawan:
                            @if($item->konfrimasi_penduduk === 'Y')
                                <strong class="text-emerald-700">Disetujui (Y)</strong>
                            @elseif($item->konfrimasi_penduduk === 'T')
                                <strong class="text-rose-700">Ditolak (T) — {{ $item->catatan_penduduk }}</strong>
                            @else
                                <strong class="text-amber-600">Menunggu</strong>
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Form Update Konfirmasi Admin (Status Y/T & Catatan Ditolak) -->
                <div class="lg:col-span-5 bg-slate-50 rounded-xl border border-slate-200 p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-slate-800">Keputusan Admin (Y / T)</span>
                        @if($item->konfrimasi_admin === 'Y')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">Disetujui (Y)</span>
                        @elseif($item->konfrimasi_admin === 'T')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800">Ditolak (T)</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">Menunggu</span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('admin.kunjungan.update', $item->id_kunjungan) }}" class="space-y-3">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status Konfirmasi Admin (Y / T)</label>
                            <select name="status" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold bg-white">
                                <option value="Y" @selected($item->konfrimasi_admin === 'Y')>Y — Setujui Kunjungan</option>
                                <option value="T" @selected($item->konfrimasi_admin === 'T')>T — Tolak Kunjungan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Ditolak (T) / Keterangan Admin</label>
                            <input type="text" name="catatan_admin" value="{{ $item->catatan_admin }}" maxlength="255"
                                   placeholder="Wajib diisi bila status ditolak (T)..."
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs bg-white">
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold cursor-pointer">
                                Simpan Keputusan Admin
                            </button>
                            @if($item->tanggal_waktu_admin)
                                <span class="text-[11px] text-slate-400">Diperbarui: {{ \Carbon\Carbon::parse($item->tanggal_waktu_admin)->format('d/m/Y H:i') }}</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-sm text-slate-500">
                Belum ada data pengajuan kunjungan.
            </div>
        @endforelse
    </div>
</div>
@endsection
