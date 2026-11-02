<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Bergabung - Unknown Rider</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-950 text-white">

    <header class="border-b border-orange-500 bg-black px-6 py-5">
        <h1 class="text-2xl font-bold text-orange-500">
            UNKNOWN RIDER
        </h1>
    </header>

    <main class="mx-auto max-w-6xl px-6 py-10">

        <div class="mb-8">
            <h2 class="text-3xl font-bold">
                Permintaan Bergabung
            </h2>

            <p class="mt-2 text-gray-400">
                Komunitas: {{ $community->name }}
            </p>

            <p class="text-gray-400">
                Ketua: {{ $community->leader->name ?? '-' }}
            </p>
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

        <div class="overflow-x-auto rounded-xl border border-gray-700 bg-gray-900">

            <table class="w-full text-left">

                <thead class="bg-gray-800 text-gray-300">
                    <tr>
                        <th class="px-5 py-4">No.</th>
                        <th class="px-5 py-4">Nama</th>
                        <th class="px-5 py-4">Email</th>
                        <th class="px-5 py-4">Nomor HP</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-700">

                    @forelse ($requests as $index => $request)

                        <tr>
                            <td class="px-5 py-4">
                                {{ $index + 1 }}
                            </td>

                            <td class="px-5 py-4 font-semibold">
                                {{ $request->user->name }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $request->user->email }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $request->user->phone ?? '-' }}
                            </td>

                            <td class="px-5 py-4">
                                <span class="rounded-full bg-yellow-900/50 px-3 py-1 text-sm text-yellow-300">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>

                            <td class="px-5 py-4">

                                <div class="flex flex-wrap gap-2">

                                    <form
                                        action="{{ route('ketua.communities.requests.approve', [$community->id, $request->id]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Setujui permintaan bergabung ini?')"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-green-600 px-3 py-2 text-sm font-semibold hover:bg-green-700"
                                        >
                                            Setujui
                                        </button>
                                    </form>

                                    <form
                                        action="{{ route('ketua.communities.requests.reject', [$community->id, $request->id]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Tolak permintaan bergabung ini?')"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold hover:bg-red-700"
                                        >
                                            Tolak
                                        </button>
                                    </form>

                                </div>

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-gray-400">
                                Belum ada permintaan bergabung.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <a href="{{ route('dashboard') }}"
           class="mt-7 inline-block text-blue-400 hover:text-blue-300">
            &larr; Kembali ke Dashboard
        </a>

    </main>

</body>
</html>
