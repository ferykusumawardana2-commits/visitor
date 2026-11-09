@extends('layouts.app')

@section('title', 'Login Pemohon — Si Kunjung Pemda')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="civic-card grid grid-cols-1 md:grid-cols-12 overflow-hidden">
        <!-- Left Civic Pass Stub -->
        <div class="md:col-span-5 bg-[#12201B] text-[#FFFDF9] p-8 flex flex-col justify-between border-b-2 md:border-b-0 md:border-r-2 border-[#12201B]">
            <div class="space-y-4">
                <span class="inline-block px-2.5 py-1 bg-[#C8552B] text-white font-mono-code text-[10px] font-bold uppercase tracking-widest">
                    KARTU AKSES TAMU
                </span>
                <h2 class="font-serif-display text-3xl font-bold leading-tight">
                    Gerbang Pemohon Si Kunjung Pemda
                </h2>
                <p class="text-xs text-stone-300 leading-relaxed">
                    Gunakan akun terdaftar Anda untuk mengajukan permohonan kunjungan, memperbarui jadwal, atau memantau catatan persetujuan dari Admin dan Karyawan.
                </p>
            </div>

            <!-- Interactive Quick-Fill Demo Credentials -->
            <div class="mt-8 p-4 bg-[#192B24] border border-stone-700 space-y-2">
                <p class="font-mono-code text-[10px] text-[#C8963E] uppercase font-bold">// AKUN DEMO PEMOHON</p>
                <div class="font-mono-code text-xs text-stone-200 space-y-0.5">
                    <p>Email: <strong class="text-white">pemohon@example.com</strong></p>
                    <p>Sandi: <strong class="text-white">pemohon123</strong></p>
                </div>
                <button type="button"
                        onclick="document.getElementById('login_email').value='pemohon@example.com'; document.getElementById('login_pass').value='pemohon123';"
                        class="w-full mt-2 py-1.5 px-3 bg-[#C8552B] hover:bg-[#C8963E] hover:text-[#12201B] text-white font-mono-code text-[11px] font-bold uppercase transition cursor-pointer">
                    Isi Otomatis Akun Demo &rarr;
                </button>
            </div>
        </div>

        <!-- Right Form Area -->
        <div class="md:col-span-7 p-8 sm:p-10 bg-[#FFFDF9]">
            <div class="pb-4 border-b-2 border-[#12201B] mb-6">
                <span class="font-mono-code text-[11px] font-bold uppercase text-[#C8552B]">// FORMULIR OTENTIKASI TAMU</span>
                <h1 class="font-serif-display text-2xl font-bold text-[#12201B] mt-0.5">Masuk ke Portal Pemohon</h1>
            </div>

            <form method="POST" action="{{ route('pemohon.login.authenticate') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block font-mono-code text-xs font-bold uppercase text-[#12201B] mb-1.5">Email / Nomor Telepon</label>
                    <input type="text" id="login_email" name="email" value="{{ old('email') }}" required placeholder="pemohon@example.com"
                           class="w-full bg-[#F5F2EB] border-[1.5px] border-[#12201B] px-4 py-3 text-sm font-medium focus:outline-none focus:bg-white">
                </div>
                <div>
                    <label class="block font-mono-code text-xs font-bold uppercase text-[#12201B] mb-1.5">Kata Sandi</label>
                    <input type="password" id="login_pass" name="password" required placeholder="••••••••"
                           class="w-full bg-[#F5F2EB] border-[1.5px] border-[#12201B] px-4 py-3 text-sm font-medium focus:outline-none focus:bg-white">
                </div>
                <button type="submit" class="w-full py-3.5 civic-btn-primary font-mono-code text-xs font-bold uppercase tracking-widest cursor-pointer">
                    Otorisasi &amp; Masuk &rarr;
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-[#D6CFC0] flex flex-wrap items-center justify-between gap-2 text-xs">
                <span class="text-[#54615B]">Belum memiliki kartu akses?</span>
                <a href="{{ route('pemohon.register') }}" class="font-mono-code font-bold text-[#C8552B] hover:underline uppercase">
                    + Daftar Pemohon Baru
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
