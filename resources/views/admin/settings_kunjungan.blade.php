@extends('layouts.dashboard')

@section('title', 'Setting Kunjungan (Admin)')
@section('header', 'Setting Kunjungan — Admin Si Kunjung Pemda')
@section('subheader', 'Kelola jadwal kunjungan: id_penduduk, jabatan, tanggal, jam_aktif, jam_non_aktif, dan status (Y/T).')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Form Tambah Setting Kunjungan (Create) -->
    <div class="lg:col-span-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
            <h2 class="text-base font-extrabold text-slate-900 mb-1">Tambah Setting Kunjungan</h2>
            <p class="text-xs text-slate-500 mb-4">Atur jadwal kunjungan untuk karyawan/pejabat Si Kunjung Pemda.</p>

            <form method="POST" action="{{ route('admin.settings-kunjungan.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Karyawan / Penduduk (id_penduduk)</label>
                    <select name="id_penduduk" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs font-medium">
                        <option value="">-- Pilih Karyawan / Penduduk --</option>
                        @foreach($pendudukList as $p)
                            <option value="{{ $p->id_penduduk }}" @selected((string) old('id_penduduk') === (string) $p->id_penduduk)>
                                [#{{ $p->id_penduduk }}] {{ $p->nama_penduduk }} ({{ $p->status }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jabatan</label>
                    <select name="id_jabatan" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs font-medium">
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach($jabatanList as $j)
                            <option value="{{ $j->id_jabatan }}" @selected((string) old('id_jabatan') === (string) $j->id_jabatan)>
                                {{ $j->nama_jabatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', now()->addDay()->toDateString()) }}" required
                           class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jam Aktif</label>
                        <input type="time" name="jam_aktif" value="{{ old('jam_aktif', '08:00') }}" required
                               class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jam Non-Aktif</label>
                        <input type="time" name="jam_non_aktif" value="{{ old('jam_non_aktif', '16:00') }}" required
                               class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status (Y / T)</label>
                    <select name="status" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs font-semibold">
                        <option value="Y" @selected(old('status', 'Y') === 'Y')>Y — Aktif (Dibuka)</option>
                        <option value="T" @selected(old('status') === 'T')>T — Non-Aktif (Ditutup)</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider cursor-pointer">
                    + Simpan Setting Kunjungan
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Setting Kunjungan (Read, Update, Delete) -->
    <div class="lg:col-span-8">
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-base font-extrabold text-slate-900">Daftar Setting Kunjungan</h2>
                <span class="text-xs font-semibold text-slate-500">{{ $settingsList->count() }} jadwal</span>
            </div>

            <div class="divide-y divide-slate-200">
                @forelse($settingsList as $item)
                    <div class="p-5 space-y-3">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <p class="text-sm font-extrabold text-slate-900">
                                    [ID Penduduk: #{{ $item->jabatanPenduduk?->id_penduduk }}]
                                    {{ $item->jabatanPenduduk?->penduduk?->nama_penduduk ?? '-' }}
                                </p>
                                <p class="text-xs font-bold text-indigo-600">
                                    Jabatan: {{ $item->jabatanPenduduk?->jabatan?->nama_jabatan ?? '-' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-slate-600">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                    ({{ substr($item->jam_aktif, 0, 5) }} - {{ substr($item->jam_non_aktif, 0, 5) }})
                                </span>
                                @if($item->status === 'Y')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Status: Y</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">Status: T</span>
                                @endif
                            </div>
                        </div>

                        <details class="bg-slate-50 rounded-xl border border-slate-200 p-3">
                            <summary class="cursor-pointer text-xs font-bold text-indigo-600">Edit / Hapus Setting Kunjungan #{{ $item->id_settings_kunjungan }}</summary>
                            <div class="mt-3 pt-3 border-t border-slate-200 space-y-3">
                                <form method="POST" action="{{ route('admin.settings-kunjungan.update', $item->id_settings_kunjungan) }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Penduduk</label>
                                        <select name="id_penduduk" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                            @foreach($pendudukList as $p)
                                                <option value="{{ $p->id_penduduk }}" @selected($item->jabatanPenduduk?->id_penduduk === $p->id_penduduk)>
                                                    {{ $p->nama_penduduk }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Jabatan</label>
                                        <select name="id_jabatan" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                            @foreach($jabatanList as $j)
                                                <option value="{{ $j->id_jabatan }}" @selected($item->jabatanPenduduk?->id_jabatan === $j->id_jabatan)>
                                                    {{ $j->nama_jabatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Tanggal</label>
                                        <input type="date" name="tanggal" value="{{ $item->tanggal }}" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Jam Aktif</label>
                                        <input type="time" name="jam_aktif" value="{{ substr($item->jam_aktif, 0, 5) }}" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Jam Non-Aktif</label>
                                        <input type="time" name="jam_non_aktif" value="{{ substr($item->jam_non_aktif, 0, 5) }}" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Status (Y/T)</label>
                                        <select name="status" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                            <option value="Y" @selected($item->status === 'Y')>Y (Aktif)</option>
                                            <option value="T" @selected($item->status === 'T')>T (Non-Aktif)</option>
                                        </select>
                                    </div>
                                    <div class="sm:col-span-3 flex justify-between items-center pt-1">
                                        <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-bold cursor-pointer">
                                            Update Jadwal
                                        </button>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('admin.settings-kunjungan.destroy', $item->id_settings_kunjungan) }}" class="flex justify-end">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus setting kunjungan ini?')"
                                            class="px-3 py-1 rounded-lg bg-rose-600 text-white text-xs font-bold cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </details>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-slate-500">Belum ada setting jadwal kunjungan.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
