@extends('layouts.guest')

@section('title', 'Dashboard Admin')

@section('content')
<div class="rounded-2xl border border-gray-800 bg-gray-900 p-8 shadow-xl">
    <h2 class="text-2xl font-bold">Dashboard Admin</h2>

    <p class="mt-3 text-gray-400">
        Selamat datang, {{ Auth::user()->name }}.
    </p>

    <div class="mt-6 grid grid-cols-1 gap-4">
        <a href="{{ route('admin.communities.index') }}"
            class="block rounded-lg bg-gray-800 p-4 hover:bg-gray-700">
            <h3 class="font-semibold">Manajemen Komunitas</h3>
            <p class="mt-1 text-sm text-gray-400">
                Membuat dan mengelola komunitas.
            </p>
        </a>

        <a href="{{ route('admin.communities.members.index', 1) }}"
           class="rounded-lg bg-gray-800 p-4 hover:bg-gray-700">
            <h3 class="font-semibold">Manajemen Anggota</h3>
            <p class="mt-1 text-sm text-gray-400">
                Melihat dan mengelola data anggota.
            </p>
        </a>
    </div>

    <form action="{{ route('logout') }}" method="POST" class="mt-6">
        @csrf
        <button type="submit"
            class="rounded-lg bg-red-600 px-5 py-3 font-semibold hover:bg-red-700">
            Logout
        </button>
    </form>
</div>
@endsection
