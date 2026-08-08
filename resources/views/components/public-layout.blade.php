<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Siedu' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white border-b">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('landing') }}" class="font-bold text-xl">Siedu</a>
            <div class="flex gap-6 items-center text-sm">
                <a href="{{ route('landing') }}" class="hover:text-gray-500">Beranda</a>
                <a href="{{ route('katalog') }}" class="hover:text-gray-500">Kelas</a>
                <a href="{{ route('about') }}" class="hover:text-gray-500">Tentang</a>

                @auth
                    <a href="{{ route('dashboard') }}" class="bg-gray-800 text-white px-4 py-2 rounded-md">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-gray-500">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-gray-800 text-white px-4 py-2 rounded-md">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <footer class="border-t mt-12 py-6 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} Siedu — Sistem Informasi Pendaftaran Les
    </footer>

</body>
</html>
