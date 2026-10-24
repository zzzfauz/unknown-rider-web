@extends('layouts.guest')

@section('title', 'Registrasi')

@section('content')
<div class="rounded-2xl border border-gray-800 bg-gray-900 p-8 shadow-xl">
    <h2 class="text-2xl font-semibold">Buat Akun</h2>
    <p class="mt-2 text-sm text-gray-400">
        Bergabung dengan komunitas Unknown Rider.
    </p>

    @if ($errors->any())
        <div class="mt-5 rounded-lg border border-red-800 bg-red-950 p-3 text-sm text-red-300">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('register.store') }}" method="POST" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="name" class="mb-2 block text-sm">Nama lengkap</label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name') }}"
                autocomplete="name"
                required
                class="w-full rounded-lg border border-gray-700 bg-gray-950 px-4 py-3 outline-none focus:border-blue-500"
                placeholder="Nama kamu"
            >
        </div>

        <div>
            <label for="email" class="mb-2 block text-sm">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                autocomplete="email"
                required
                class="w-full rounded-lg border border-gray-700 bg-gray-950 px-4 py-3 outline-none focus:border-blue-500"
                placeholder="nama@email.com"
            >
        </div>

        <div>
            <label for="phone" class="mb-2 block text-sm">Nomor telepon (opsional)</label>
            <input
                id="phone"
                name="phone"
                type="tel"
                value="{{ old('phone') }}"
                autocomplete="tel"
                class="w-full rounded-lg border border-gray-700 bg-gray-950 px-4 py-3 outline-none focus:border-blue-500"
                placeholder="08xxxxxxxxxx"
            >
        </div>

        <div>
            <label for="password" class="mb-2 block text-sm">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                autocomplete="new-password"
                minlength="8"
                required
                class="w-full rounded-lg border border-gray-700 bg-gray-950 px-4 py-3 outline-none focus:border-blue-500"
                placeholder="Minimal 8 karakter"
            >
        </div>

        <div>
            <label for="password_confirmation" class="mb-2 block text-sm">Konfirmasi password</label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                minlength="8"
                required
                class="w-full rounded-lg border border-gray-700 bg-gray-950 px-4 py-3 outline-none focus:border-blue-500"
                placeholder="Ulangi password"
            >
        </div>

        <button
            type="submit"
            class="w-full rounded-lg bg-blue-600 px-4 py-3 font-semibold hover:bg-blue-500"
        >
            Daftar
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-400">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-medium text-blue-400 hover:text-blue-300">
            Login
        </a>
    </p>
</div>
@endsection