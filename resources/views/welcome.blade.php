<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Campus LMS') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">

    <div class="min-h-screen flex flex-col">

        <nav class="max-w-7xl mx-auto w-full px-6 py-6 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-application-logo class="h-9 w-auto fill-current text-blue-700" />
                <span class="font-semibold text-lg text-gray-800">Campus LMS</span>
            </div>

            @if (Route::has('login'))
                <div class="flex items-center gap-3">
                    @auth
                        <x-button href="{{ route('dashboard') }}" size="sm">Dashboard</x-button>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-blue-700">Log in</a>
                        @if (Route::has('register'))
                            <x-button href="{{ route('register') }}" size="sm">Register</x-button>
                        @endif
                    @endauth
                </div>
            @endif
        </nav>

        <main class="flex-1 flex items-center">
            <div class="max-w-7xl mx-auto w-full px-6 py-16">
                <div class="max-w-2xl">
                    <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 leading-tight">
                        Learning Management System untuk Kampus
                    </h1>
                    <p class="mt-6 text-lg text-gray-600">
                        Kelola perkuliahan, materi pembelajaran, tugas, kuis, dan nilai dalam satu platform terintegrasi — untuk admin, dosen, dan mahasiswa.
                    </p>

                    <div class="mt-8 flex items-center gap-4">
                        @auth
                            <x-button href="{{ route('dashboard') }}">Buka Dashboard</x-button>
                        @else
                            <x-button href="{{ route('login') }}">Log in</x-button>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-sm font-medium text-gray-700 hover:text-blue-700">
                                    Buat akun &rarr;
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="mt-16 grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <x-card>
                        <p class="font-semibold text-gray-800">Manajemen Akademik</p>
                        <p class="text-sm text-gray-500 mt-2">Fakultas, program studi, mata kuliah, KRS, dan kelas dalam satu sistem.</p>
                    </x-card>
                    <x-card>
                        <p class="font-semibold text-gray-800">Konten & Penilaian</p>
                        <p class="text-sm text-gray-500 mt-2">Materi, tugas, kuis dengan auto-grading, dan nilai akhir otomatis.</p>
                    </x-card>
                    <x-card>
                        <p class="font-semibold text-gray-800">Kolaborasi</p>
                        <p class="text-sm text-gray-500 mt-2">Pengumuman dan forum diskusi antar dosen dan mahasiswa.</p>
                    </x-card>
                </div>
            </div>
        </main>

        <footer class="max-w-7xl mx-auto w-full px-6 py-6 text-center text-sm text-gray-400">
            Campus LMS &mdash; Dibangun dengan Laravel
        </footer>

    </div>

</body>
</html>