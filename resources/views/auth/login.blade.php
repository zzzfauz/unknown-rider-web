@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="rounded-2xl border border-gray-800 bg-gray-900 p-8 shadow-xl">
    <h2 class="text-2xl font-semibold">Selamat Datang</h2>
    <p class="mt-2 text-sm text-gray-400">
        Login ke akun Unknown Rider kamu.
    </p>

    @if ($errors->any())
        <div class="mt-5 rounded-lg border border-red-800 bg-red-950 p-3 text-sm text-red-300">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('login.store') }}" method="POST" class="mt-6 space-y-5">
        @csrf

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
            <label for="password" class="mb-2 block text-sm">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                autocomplete="current-password"
                required
                class="w-full rounded-lg border border-gray-700 bg-gray-950 px-4 py-3 outline-none focus:border-blue-500"
                placeholder="Masukkan password"
            >
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-400">
            <input type="checkbox" name="remember" value="1">
            Ingat saya
        </label>

        <button
            type="submit"
            class="w-full rounded-lg bg-blue-600 px-4 py-3 font-semibold hover:bg-blue-500"
        >
            Login
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-400">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-medium text-blue-400 hover:text-blue-300">
            Daftar sekarang
        </a>
    </p>
</div>
@endsection