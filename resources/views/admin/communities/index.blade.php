@extends('layouts.guest')

@section('title', 'Manajemen Komunitas')

@section('content')
<div class="w-full max-w-5xl mx-auto rounded-2xl border border-gray-800 bg-gray-900 p-8 shadow-xl">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold">Manajemen Komunitas</h2>
            <p class="mt-2 text-gray-400">
                Kelola komunitas dan Ketua Unknown Rider.
            </p>
        </div>

        <a href="{{ route('admin.communities.create') }}"
           class="rounded-lg bg-blue-600 px-5 py-3 text-center font-semibold text-white hover:bg-blue-700">
            + Tambah Komunitas
        </a>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-lg bg-green-900/40 p-4 text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if($communities->isEmpty())
        <div class="rounded-xl border border-gray-700 bg-gray-800 p-8 text-center">
            <h3 class="text-lg font-semibold">Belum Ada Komunitas</h3>
            <p class="mt-2 text-gray-400">
                Silakan buat komunitas pertama Unknown Rider.
            </p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-gray-700 text-gray-400">
                    <tr>
                        <th class="p-4">Nama Komunitas</th>
                        <th class="p-4">Ketua</th>
                        <th class="p-4">Jumlah Anggota</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($communities as $community)
                        <tr class="border-b border-gray-800">
                            <td class="p-4 font-semibold">
                                {{ $community->name }}
                            </td>

                            <td class="p-4">
                                {{ $community->leader?->name ?? 'Belum ditentukan' }}
                            </td>

                            <td class="p-4">
                                {{ $community->members_count }} anggota
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-8">
        <a href="{{ route('admin.dashboard') }}"
           class="text-blue-400 hover:text-blue-300">
            ← Kembali ke Dashboard Admin
        </a>
    </div>

</div>
@endsection