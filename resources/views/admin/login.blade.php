@extends('layouts.app')

@section('title', 'Login Admin — Si Kunjung Pemda')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="civic-card grid grid-cols-1 md:grid-cols-12 overflow-hidden">
        <!-- Left Authority Seal -->
        <div class="md:col-span-5 bg-[#12201B] text-[#FFFDF9] p-8 flex flex-col justify-between border-b-2 md:border-b-0 md:border-r-2 border-[#12201B]">
            <div class="space-y-4">
                <span class="inline-block px-2.5 py-1 bg-[#C8963E] text-[#12201B] font-mono-code text-[10px] font-bold uppercase tracking-widest">
                    OTORITAS ADMINISTRATOR
                </span>
                <h2 class="font-serif-display text-3xl font-bold leading-tight">
                    Meja Kendali Admin Si Kunjung Pemda
                </h2>
                <p class="text-xs text-stone-300 leading-relaxed">
                    Panel khusus Administrator untuk memverifikasi permohonan kunjungan (status Y/T &amp; catatan ditolak), mengatur jadwal kunjungan, dan mengelola kegiatan dinas.
                </p>
            </div>

            <div class="mt-8 p-4 bg-[#192B24] border border-stone-700 space-y-2">
                <p class="font-mono-code text-[10px] text-[#C8963E] uppercase font-bold">// AKUN ADMIN (FERRY KUSUMA WARDANA)</p>
                <div class="font-mono-code text-xs text-stone-200 space-y-0.5">
                    <p>Username: <strong class="text-white">admin</strong></p>
                    <p>Password: <strong class="text-white">admin123</strong></p>
                </div>
                <button type="button"
                        onclick="document.getElementById('admin_user').value='admin'; document.getElementById('admin_pass').value='admin123';"
                        class="w-full mt-2 py-1.5 px-3 bg-[#C8963E] hover:bg-[#C8552B] text-[#12201B] hover:text-white font-mono-code text-[11px] font-bold uppercase transition cursor-pointer">
                    Isi Otomatis Akun Admin &rarr;
                </button>
            </div>
        </div>

        <!-- Right Form -->
        <div class="md:col-span-7 p-8 sm:p-10 bg-[#FFFDF9]">
            <div class="pb-4 border-b-2 border-[#12201B] mb-6">
                <span class="font-mono-code text-[11px] font-bold uppercase text-[#C8552B]">// OTENTIKASI PETUGAS</span>
                <h1 class="font-serif-display text-2xl font-bold text-[#12201B] mt-0.5">Login Administrator</h1>
            </div>

            <form method="POST" action="{{ route('admin.login.authenticate') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block font-mono-code text-xs font-bold uppercase text-[#12201B] mb-1.5">Username / NIK Admin</label>
                    <input type="text" id="admin_user" name="username" value="{{ old('username') }}" required placeholder="admin"
                           class="w-full bg-[#F5F2EB] border-[1.5px] border-[#12201B] px-4 py-3 text-sm font-mono-code focus:outline-none focus:bg-white">
                </div>
                <div>
                    <label class="block font-mono-code text-xs font-bold uppercase text-[#12201B] mb-1.5">Password</label>
                    <input type="password" id="admin_pass" name="password" required placeholder="••••••••"
                           class="w-full bg-[#F5F2EB] border-[1.5px] border-[#12201B] px-4 py-3 text-sm focus:outline-none focus:bg-white">
                </div>
                <button type="submit" class="w-full py-3.5 civic-btn-primary font-mono-code text-xs font-bold uppercase tracking-widest cursor-pointer">
                    Masuk ke Meja Admin &rarr;
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-[#D6CFC0] flex items-center justify-between text-xs">
                <span class="text-[#54615B]">Pejabat / Staf Aparatur?</span>
                <a href="{{ route('karyawan.login') }}" class="font-mono-code font-bold text-[#1B6B53] hover:underline uppercase">
                    Buka Login Karyawan &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
