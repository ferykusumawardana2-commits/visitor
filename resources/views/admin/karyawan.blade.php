@extends('layouts.dashboard')

@section('title', 'Struktur Karyawan & Jabatan (Admin)')
@section('header', 'Struktur Karyawan / Penduduk & Jabatan — Si Kunjung Pemda')
@section('subheader', 'Kelola data master karyawan/penduduk, jabatan, dan penempatan struktur organisasi Si Kunjung Pemda.')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Kolom Kiri: Form Tambah Karyawan & Jabatan -->
    <div class="lg:col-span-4 space-y-6">
        <!-- Tambah Jabatan -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <h2 class="text-sm font-extrabold text-slate-900 mb-3">Tambah Jabatan Si Kunjung Pemda</h2>
            <form method="POST" action="{{ route('admin.jabatan.store') }}" class="flex gap-2">
                @csrf
                <input type="text" name="nama_jabatan" required maxlength="100" placeholder="Nama Jabatan Baru..."
                       class="flex-1 rounded-xl border border-slate-300 px-3 py-2 text-xs">
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold cursor-pointer">
                    + Jabatan
                </button>
            </form>
        </div>

        <!-- Tambah Karyawan / Penduduk -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
            <h2 class="text-base font-extrabold text-slate-900 mb-1">Tambah Karyawan / Penduduk</h2>
            <p class="text-xs text-slate-500 mb-4">Tambahkan akun Admin atau Karyawan (Penduduk) beserta jabatannya.</p>

            <form method="POST" action="{{ route('admin.karyawan.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_penduduk" required maxlength="100" placeholder="Nama Lengkap"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">NIK (16 Digit)</label>
                        <input type="text" name="nik" required minlength="16" maxlength="16" value="3201010000000001"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">No. KK (16 Digit)</label>
                        <input type="text" name="kk" required minlength="16" maxlength="16" value="3201010000000000"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                            <option value="L">Laki-laki (L)</option>
                            <option value="P">Perempuan (P)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Role / Status</label>
                        <select name="status" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                            <option value="Penduduk">Karyawan (Penduduk)</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jabatan di Si Kunjung Pemda</label>
                    <select name="id_jabatan" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach($jabatanList as $j)
                            <option value="{{ $j->id_jabatan }}">{{ $j->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Alamat</label>
                    <input type="text" name="alamat" required maxlength="255" placeholder="Alamat lengkap"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Username Login</label>
                        <input type="text" name="username" required placeholder="username"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Password</label>
                        <input type="password" name="password" required placeholder="Min. 4 karakter"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider cursor-pointer">
                    + Simpan Karyawan / Penduduk
                </button>
            </form>
        </div>
    </div>

    <!-- Kolom Kanan: Tabel Karyawan & Jabatan -->
    <div class="lg:col-span-8 space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-base font-extrabold text-slate-900">Daftar Karyawan / Penduduk Si Kunjung Pemda</h2>
                <span class="text-xs font-semibold text-slate-500">{{ $pendudukList->count() }} personel</span>
            </div>

            <div class="divide-y divide-slate-200">
                @foreach($pendudukList as $p)
                    @php
                        $firstJp = $p->jabatanPenduduk->first();
                    @endphp
                    <div class="p-5 space-y-2">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <p class="text-sm font-extrabold text-slate-900">
                                    [#{{ $p->id_penduduk }}] {{ $p->nama_penduduk }}
                                    <span class="text-xs font-normal text-slate-500">({{ $p->username }})</span>
                                </p>
                                <p class="text-xs font-semibold text-indigo-600">
                                    Jabatan: {{ $firstJp?->jabatan?->nama_jabatan ?? 'Belum diatur' }}
                                    &bull; NIK: {{ $p->nik }}
                                </p>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $p->status === 'Admin' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-sky-50 text-sky-700 border border-sky-200' }}">
                                {{ $p->status === 'Admin' ? 'Admin' : 'Karyawan (Penduduk)' }}
                            </span>
                        </div>

                        <details class="bg-slate-50 rounded-xl border border-slate-200 p-3">
                            <summary class="cursor-pointer text-xs font-bold text-indigo-600">Edit / Hapus Data #{{ $p->id_penduduk }}</summary>
                            <div class="mt-3 pt-3 border-t border-slate-200 space-y-3">
                                <form method="POST" action="{{ route('admin.karyawan.update', $p->id_penduduk) }}" class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Nama</label>
                                        <input type="text" name="nama_penduduk" value="{{ $p->nama_penduduk }}" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">NIK</label>
                                        <input type="text" name="nik" value="{{ $p->nik }}" required minlength="16" maxlength="16" class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">KK</label>
                                        <input type="text" name="kk" value="{{ $p->kk }}" required minlength="16" maxlength="16" class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                            <option value="L" @selected($p->jenis_kelamin === 'L')>L</option>
                                            <option value="P" @selected($p->jenis_kelamin === 'P')>P</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Status Role</label>
                                        <select name="status" class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                            <option value="Penduduk" @selected($p->status === 'Penduduk')>Penduduk (Karyawan)</option>
                                            <option value="Admin" @selected($p->status === 'Admin')>Admin</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Jabatan</label>
                                        <select name="id_jabatan" class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                            @foreach($jabatanList as $j)
                                                <option value="{{ $j->id_jabatan }}" @selected($firstJp?->id_jabatan === $j->id_jabatan)>
                                                    {{ $j->nama_jabatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Username</label>
                                        <input type="text" name="username" value="{{ $p->username }}" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Password Baru (Opsional)</label>
                                        <input type="password" name="password" placeholder="Kosongkan jika tetap" class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Alamat</label>
                                        <input type="text" name="alamat" value="{{ $p->alamat }}" required class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white">
                                    </div>
                                    <div class="sm:col-span-3">
                                        <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-bold cursor-pointer">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('admin.karyawan.destroy', $p->id_penduduk) }}" class="flex justify-end">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus data karyawan ini?')"
                                            class="px-3 py-1 rounded-lg bg-rose-600 text-white text-xs font-bold cursor-pointer">
                                        Hapus Karyawan
                                    </button>
                                </form>
                            </div>
                        </details>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
