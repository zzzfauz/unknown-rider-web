<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Anggota - Unknown Rider</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-950 text-white">

    <header class="border-b border-orange-500 bg-black px-6 py-5">
        <h1 class="text-2xl font-bold text-orange-500">
            UNKNOWN RIDER
        </h1>
    </header>

    <main class="mx-auto max-w-2xl px-6 py-12">

        <div class="rounded-xl border border-gray-700 bg-gray-900 p-8">

            <h2 class="text-3xl font-bold">
                Tambah Anggota
            </h2>

            <p class="mt-2 text-gray-400">
                Komunitas: {{ $community->name }}
            </p>

            @if ($errors->any())
                <div class="mt-5 rounded-lg bg-red-900/50 p-4 text-red-300">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($users->isEmpty())
                <div class="mt-7 rounded-lg bg-gray-800 p-6 text-center">
                    <h3 class="font-semibold">
                        Tidak Ada Pengguna Tersedia
                    </h3>

                    <p class="mt-2 text-gray-400">
                        Semua pengguna dengan role Member sudah bergabung
                        atau belum tersedia.
                    </p>
                </div>
            @else
                <form
                    action="{{ route('admin.communities.members.store', $community->id) }}"
                    method="POST"
                    class="mt-7"
                >
                    @csrf

                    <label for="user_id"
                        class="mb-2 block font-semibold">
                        Pilih Anggota
                    </label>

                    <select
                        name="user_id"
                        id="user_id"
                        required
                        class="w-full rounded-lg border border-gray-700 bg-gray-950 px-4 py-3 text-white"
                    >
                        <option value="">-- Pilih Pengguna --</option>

                        @foreach ($users as $user)
                            <option
                                value="{{ $user->id }}"
                                @selected(old('user_id') == $user->id)
                            >
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>

                    <div class="mt-7 flex flex-wrap gap-3">
                        <button
                            type="submit"
                            class="rounded-lg bg-blue-600 px-6 py-3 font-semibold hover:bg-blue-700"
                        >
                            Tambahkan Anggota
                        </button>

                        <a
                            href="{{ route('admin.communities.members.index', $community->id) }}"
                            class="rounded-lg bg-gray-700 px-6 py-3 font-semibold hover:bg-gray-600"
                        >
                            Batal
                        </a>
                    </div>
                </form>
            @endif

        </div>

    </main>

</body>
</html>