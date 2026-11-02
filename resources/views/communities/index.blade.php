<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Komunitas - Unknown Rider</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-950 text-white">

    <header class="border-b border-orange-500 bg-black px-6 py-5">
        <h1 class="text-2xl font-bold text-orange-500">
            UNKNOWN RIDER
        </h1>
    </header>

    <main class="mx-auto max-w-5xl px-6 py-10">

        <h2 class="mb-2 text-3xl font-bold">
            Daftar Komunitas
        </h2>

        <p class="mb-8 text-gray-400">
            Temukan komunitas dan ajukan permintaan bergabung.
        </p>

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

        @forelse ($communities as $community)

            <div class="mb-5 rounded-xl border border-gray-700 bg-gray-900 p-6">

                <h3 class="text-xl font-bold text-orange-500">
                    {{ $community->name }}
                </h3>

                <p class="mt-3 text-gray-300">
                    {{ $community->description ?? 'Tidak ada deskripsi.' }}
                </p>

                <div class="mt-4 space-y-2 text-sm text-gray-400">
                    <p>
                        Ketua:
                        {{ $community->leader->name ?? '-' }}
                    </p>

                    <p>
                        Jumlah anggota:
                        {{ $community->members_count }}
                    </p>
                </div>

                
                {{-- Status keanggotaan dan tombol pengajuan --}}
@if ($userMembership)

    @if ($userMembership->community_id == $community->id)

        @if ($userMembership->status === 'active')
            <span class="mt-5 inline-block rounded-lg bg-green-900 px-4 py-3 text-green-300">
                Sudah Bergabung
            </span>
        @else
            <span class="mt-5 inline-block rounded-lg bg-yellow-900 px-4 py-3 text-yellow-300">
                Menunggu Persetujuan
            </span>
        @endif

    @else

        <span class="mt-5 inline-block rounded-lg bg-gray-700 px-4 py-3 text-gray-300">
            Sudah Memiliki Komunitas
        </span>

    @endif

@else

    <form
        action="{{ route('communities.join', $community->id) }}"
        method="POST"
        class="mt-5"
    >
        @csrf

        <button
            type="submit"
            class="rounded-lg bg-blue-600 px-5 py-3 font-semibold hover:bg-blue-700"
        >
            Ajukan Bergabung
        </button>
    </form>

@endif

            </div>

        @empty

            <div class="rounded-xl border border-gray-700 bg-gray-900 p-8 text-center">
                <h3 class="text-xl font-bold">
                    Belum Ada Komunitas
                </h3>

                <p class="mt-2 text-gray-400">
                    Saat ini belum tersedia komunitas untuk bergabung.
                </p>
            </div>

        @endforelse

        <a href="{{ route('dashboard') }}"
           class="mt-5 inline-block text-blue-400 hover:text-blue-300">
            &larr; Kembali ke Dashboard
        </a>

    </main>

</body>
</html>