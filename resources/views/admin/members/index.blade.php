<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Anggota - Unknown Rider</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-950 text-white">

    <header class="border-b border-orange-500 bg-black px-6 py-5">
        <h1 class="text-2xl font-bold text-orange-500">
            UNKNOWN RIDER
        </h1>
    </header>

    <main class="mx-auto max-w-6xl px-6 py-10">

        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold">
                    Manajemen Anggota
                </h2>
                <p class="mt-2 text-gray-400">
                    Komunitas: {{ $community->name }}
                </p>
                <p class="text-gray-400">
                    Ketua: {{ $community->leader->name ?? '-' }}
                </p>
            </div>

            <a href="{{ route('admin.communities.members.create', $community->id) }}"
               class="rounded-lg bg-blue-600 px-5 py-3 font-semibold hover:bg-blue-700">
                + Tambah Anggota
            </a>
        </div>

        @if (session('success'))
            <div class="mb-5 rounded-lg bg-green-900/50 p-4 text-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 rounded-lg bg-red-900/50 p-4 text-red-300">
                {{ session('error') }}
            </div>
        @endif

        {{-- Form Pergantian Ketua --}}
        @php
            $eligibleMembers = $members->filter(function ($membership) use ($community) {
                return $membership->status === 'active'
                    && in_array($membership->user->role, ['member', 'ketua'], true)
                    && $membership->user_id != $community->leader_id;
            });
        @endphp

        <div class="mb-8 rounded-xl border border-gray-700 bg-gray-900 p-6">

            <h3 class="text-xl font-bold text-orange-500">
                Pergantian Ketua Komunitas
            </h3>

            <p class="mt-2 text-gray-400">
                Pilih anggota aktif untuk menjadi Ketua komunitas yang baru.
            </p>

            @if ($eligibleMembers->isNotEmpty())

                <form
                    action="{{ route('admin.communities.leader.update', $community->id) }}"
                    method="POST"
                    class="mt-5"
                    onsubmit="return confirm('Yakin ingin mengganti Ketua komunitas?')"
                >
                    @csrf
                    @method('PUT')

                    <label for="user_id" class="mb-2 block font-semibold">
                        Pilih Ketua Baru
                    </label>

                    <select
                        name="user_id"
                        id="user_id"
                        required
                        class="w-full rounded-lg border border-gray-700 bg-gray-950 px-4 py-3 text-white focus:border-blue-500 focus:outline-none"
                    >
                        <option value="">-- Pilih Anggota --</option>

                        @foreach ($eligibleMembers as $membership)
                            <option
                                value="{{ $membership->user_id }}"
                                {{ old('user_id') == $membership->user_id ? 'selected' : '' }}
                            >
                                {{ $membership->user->name }}
                                ({{ $membership->user->email }})
                            </option>
                        @endforeach
                    </select>

                    @error('user_id')
                        <p class="mt-2 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror

                    <button
                        type="submit"
                        class="mt-4 rounded-lg bg-orange-600 px-5 py-3 font-semibold hover:bg-orange-700"
                    >
                        Ganti Ketua
                    </button>
                </form>

            @else

                <div class="mt-5 rounded-lg bg-gray-800 p-4 text-gray-400">
                    Belum ada anggota aktif lain yang dapat ditunjuk sebagai Ketua.
                </div>

            @endif

        </div>

        {{-- Tabel Anggota --}}
        <div class="overflow-x-auto rounded-xl border border-gray-700 bg-gray-900">

            <table class="w-full text-left">

                <thead class="bg-gray-800 text-gray-300">
                    <tr>
                        <th class="px-5 py-4">No.</th>
                        <th class="px-5 py-4">Nama</th>
                        <th class="px-5 py-4">Email</th>
                        <th class="px-5 py-4">Nomor HP</th>
                        <th class="px-5 py-4">Role</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-700">

                    @forelse ($members as $index => $membership)

                        <tr>
                            <td class="px-5 py-4">
                                {{ $index + 1 }}
                            </td>

                            <td class="px-5 py-4 font-semibold">
                                {{ $membership->user->name }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $membership->user->email }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $membership->user->phone ?? '-' }}
                            </td>

                            <td class="px-5 py-4">
                                {{ ucfirst($membership->user->role) }}
                            </td>

                            <td class="px-5 py-4">
                                {{ ucfirst($membership->status) }}
                            </td>

                            <td class="px-5 py-4">

                                @if ($membership->user_id != $community->leader_id)

                                    <form
                                        action="{{ route('admin.communities.members.destroy', [$community->id, $membership->id]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin mengeluarkan anggota ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold hover:bg-red-700"
                                        >
                                            Keluarkan
                                        </button>
                                    </form>

                                @else

                                    <span class="text-gray-400">
                                        Ketua Komunitas
                                    </span>

                                @endif

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7"
                                class="px-5 py-10 text-center text-gray-400">
                                Belum ada anggota komunitas.
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        <a href="{{ route('admin.communities.index') }}"
           class="mt-7 inline-block text-blue-400 hover:text-blue-300">
            &larr; Kembali ke Manajemen Komunitas
        </a>

    </main>

</body>
</html>