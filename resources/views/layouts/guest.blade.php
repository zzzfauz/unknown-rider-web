<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Unknown Rider</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-950 text-white">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold tracking-wide">UNKNOWN RIDER</h1>
                <p class="mt-2 text-sm text-gray-400">
                    Platform Digital Komunitas dan Touring
                </p>
            </div>

            @yield('content')

            <p class="mt-6 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} Unknown Rider
            </p>
        </div>
    </main>
</body>
</html>