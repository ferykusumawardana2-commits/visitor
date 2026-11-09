@extends('layouts.app')

@section('title', 'Daftar Pemohon — Si Kunjung Pemda')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    <div class="civic-card overflow-hidden">
        <div class="px-8 py-5 bg-[#12201B] text-[#FFFDF9] flex items-center justify-between">
            <div>
                <span class="font-mono-code text-[10px] uppercase tracking-widest text-[#C8963E] block">// FORMULIR REGISTRASI TAMU</span>
                <h1 class="font-serif-display text-2xl font-bold">Pendaftaran Pemohon Baru</h1>
            </div>
            <span class="px-2.5 py-1 bg-[#C8552B] text-white font-mono-code text-xs font-bold">CREATE</span>
        </div>

        <form method="POST" action="{{ route('pemohon.register.store') }}" class="p-8 space-y-5 bg-[#FFFDF9]">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block font-mono-code text-xs font-bold uppercase text-[#12201B] mb-1.5">01 / Nama Lengkap Pemohon</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Rizky Pratama"
                           class="w-full bg-[#F5F2EB] border-[1.5px] border-[#12201B] px-4 py-3 text-sm focus:outline-none focus:bg-white">
                </div>
                <div>
                    <label class="block font-mono-code text-xs font-bold uppercase text-[#12201B] mb-1.5">02 / Nomor Telepon / WA</label>
                    <input type="text" name="telepon" value="{{ old('telepon') }}" required maxlength="15" placeholder="081234567890"
                           class="w-full bg-[#F5F2EB] border-[1.5px] border-[#12201B] px-4 py-3 text-sm font-mono-code focus:outline-none focus:bg-white">
                </div>
                <div>
                    <label class="block font-mono-code text-xs font-bold uppercase text-[#12201B] mb-1.5">03 / Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                           class="w-full bg-[#F5F2EB] border-[1.5px] border-[#12201B] px-4 py-3 text-sm focus:outline-none focus:bg-white">
                </div>
                <div>
                    <label class="block font-mono-code text-xs font-bold uppercase text-[#12201B] mb-1.5">04 / Kata Sandi</label>
                    <input type="password" name="password" required placeholder="Minimal 4 karakter"
                           class="w-full bg-[#F5F2EB] border-[1.5px] border-[#12201B] px-4 py-3 text-sm focus:outline-none focus:bg-white">
                </div>
                <div>
                    <label class="block font-mono-code text-xs font-bold uppercase text-[#12201B] mb-1.5">05 / Ulangi Kata Sandi</label>
                    <input type="password" name="password_confirmation" required placeholder="Konfirmasi kata sandi"
                           class="w-full bg-[#F5F2EB] border-[1.5px] border-[#12201B] px-4 py-3 text-sm focus:outline-none focus:bg-white">
                </div>
            </div>

            <div class="pt-3 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-[#D6CFC0]">
                <a href="{{ route('pemohon.login') }}" class="font-mono-code text-xs font-bold text-[#54615B] hover:text-[#12201B]">
                    &larr; Sudah punya akun? Login di sini
                </a>
                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 civic-btn-terra font-mono-code text-xs font-bold uppercase tracking-widest cursor-pointer">
                    Terbitkan Kartu Pemohon &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
