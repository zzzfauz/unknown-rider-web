@extends('layouts.guest')

@section('title', 'Tambah Komunitas')

@section('content')
<div class="w-full max-w-2xl mx-auto rounded-2xl border border-gray-800 bg-gray-900 p-8 shadow-xl">

    <h2 class="text-2xl font-bold">Tambah Komunitas</h2>

    <p class="mt-2 mb-8 text-gray-400">
        Masukkan informasi komunitas dan tentukan Ketua.
    </p>

    @if($errors->any())
        <div class="mb-6 rounded-lg bg-red-900/40 p-4 text-red-300">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.communities.store') }}" method="POST">
        @csrf

        <div class="mb-5">
            <label class="mb-2 block font-medium">Nama Komunitas</label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                placeholder="Contoh: Unknown Rider Bandung"
                class="w-full rounded-lg border border-gray-700 bg-gray-950 p-3 text-white focus:border-blue-500 focus:outline-none"
            >
        </div>

        <div class="mb-5">
            <label class="mb-2 block font-medium">Deskripsi</label>

            <textarea
                name="description"
                rows="4"
                placeholder="Deskripsi komunitas..."
                class="w-full rounded-lg border border-gray-700 bg-gray-950 p-3 text-white focus:border-blue-500 focus:outline-none"
            >{{ old('description') }}</textarea>
        </div>

        <div class="mb-5">
            <label class="mb-2 block font-medium">Logo (Opsional)</label>

            <input
                type="text"
                name="logo"
                value="{{ old('logo') }}"
                placeholder="Path atau URL logo"
                class="w-full rounded-lg border border-gray-700 bg-gray-950 p-3 text-white focus:border-blue-500 focus:outline-none"
            >
        </div>

        <div class="mb-8">
            <label class="mb-2 block font-medium">Pilih Ketua Komunitas</label>

            <select
                name="leader_id"
                required
                class="w-full rounded-lg border border-gray-700 bg-gray-950 p-3 text-white focus:border-blue-500 focus:outline-none"
            >
                <option value="">-- Pilih Pengguna --</option>

                @foreach($users as $user)
                    <option
                        value="{{ $user->id }}"
                        @selected(old('leader_id') == $user->id)
                    >
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>

            <p class="mt-2 text-sm text-gray-400">
                Hanya pengguna dengan role Member yang dapat dipilih.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <button
                type="submit"
                class="rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700"
            >
                Simpan Komunitas
            </button>

            <a
                href="{{ route('admin.communities.index') }}"
                class="rounded-lg bg-gray-700 px-6 py-3 text-center font-semibold text-white hover:bg-gray-600"
            >
                Batal
            </a>
        </div>

    </form>
</div>
@endsection