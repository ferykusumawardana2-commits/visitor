@extends('layouts.dashboard')

@section('title', 'Kegiatan (Admin)')
@section('header', 'Manajemen Kegiatan — Admin Si Kunjung Pemda')
@section('subheader', 'Kelola agenda kegiatan karyawan: id_penduduk, jabatan, tanggal_awal, tanggal_akhir, dan deskripsi kegiatan.')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Form Tambah Kegiatan (Create) -->
    <div class="lg:col-span-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
            <h2 class="text-base font-extrabold text-slate-900 mb-1">Tambah Kegiatan Baru</h2>
            <p class="text-xs text-slate-500 mb-4">Catat agenda kegiatan karyawan/pejabat Si Kunjung Pemda.</p>

            <form method="POST" action="{{ route('admin.kegiatan.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Karyawan / Penduduk (id_penduduk)</label>
                    <select name="id_penduduk" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs font-medium">
                        <option value="">-- Pilih Karyawan / Penduduk --</option>
                        @foreach($pendudukList as $p)
                            <option value="{{ $p->id_penduduk }}" @selected((string) old('id_penduduk') === (string) $p->id_penduduk)>
                                [#{{ $p->id_penduduk }}] {{ $p->nama_penduduk }}
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

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" value="{{ old('tanggal_awal', now()->toDateString()) }}" required
                               class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" value="{{ old('tanggal_akhir', now()->addDays(2)->toDateString()) }}" required
                               class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Kegiatan</label>
                    <textarea name="kegiatan" rows="3" required maxlength="255" placeholder="Deskripsi kegiatan pejabat/karyawan..."
                              class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">{{ old('kegiatan') }}</textarea>
                </div>

                <button type="submit" class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider cursor-pointer">
                    + Simpan Kegiatan
                </button>
            </form>
        </div>
    </div>

    <!-- Daftar Kegiatan (Read, Update, Delete) -->
    <div class="lg:col-span-8">
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-base font-extrabold text-slate-900">Daftar Kegiatan Karyawan Si Kunjung Pemda</h2>
                <span class="text-xs font-semibold text-slate-500">{{ $kegiatanList->count() }} kegiatan</span>
            </div>

            <div class="divide-y divide-slate-200">
                @forelse($kegiatanList as $item)
                    <div class="p-5 space-y-3">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <div>
                                <p class="text-xs font-bold text-indigo-600">
                                    [ID Penduduk: #{{ $item->jabatanPenduduk?->id_penduduk }}]
                                    {{ $item->jabatanPenduduk?->penduduk?->nama_penduduk ?? '-' }}
                                    &bull; {{ $item->jabatanPenduduk?->jabatan?->nama_jabatan ?? '-' }}
                                </p>
                                <h3 class="text-sm font-extrabold text-slate-900 mt-1">{{ $item->kegiatan }}</h3>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold">
                                {{ \Carbon\Carbon::parse($item->tanggal_awal)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') }}
                            </span>
                        </div>

                        <details class="bg-slate-50 rounded-xl border border-slate-200 p-3">
                            <summary class="cursor-pointer text-xs font-bold text-indigo-600">Edit / Hapus Kegiatan #{{ $item->id_settings_kegiatan }}</summary>
                            <div class="mt-3 pt-3 border-t border-slate-200 space-y-3">
                                <form method="POST" action="{{ route('admin.kegiatan.update', $item->id_settings_kegiatan) }}" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
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
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Tanggal Awal</label>
                                        <input type="date" name="tanggal_awal" value="{{ $item->tanggal_awal }}" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Tanggal Akhir</label>
                                        <input type="date" name="tanggal_akhir" value="{{ $item->tanggal_akhir }}" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Deskripsi Kegiatan</label>
                                        <input type="text" name="kegiatan" value="{{ $item->kegiatan }}" required maxlength="255" class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-bold cursor-pointer">
                                            Update Kegiatan
                                        </button>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('admin.kegiatan.destroy', $item->id_settings_kegiatan) }}" class="flex justify-end">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus kegiatan ini?')"
                                            class="px-3 py-1 rounded-lg bg-rose-600 text-white text-xs font-bold cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </details>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-slate-500">Belum ada data kegiatan.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
