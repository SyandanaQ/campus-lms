<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($activeYear)
                <x-alert type="info">
                    Tahun Ajaran Aktif: <strong>{{ $activeYear->year }} ({{ ucfirst($activeYear->semester) }})</strong>
                </x-alert>
            @else
                <x-alert type="warning">
                    Belum ada tahun ajaran aktif.
                </x-alert>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-card>
                    <p class="text-sm text-gray-500">Total Mahasiswa</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalStudents }}</p>
                </x-card>
                <x-card>
                    <p class="text-sm text-gray-500">Total Dosen</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalLecturers }}</p>
                </x-card>
                <x-card>
                    <p class="text-sm text-gray-500">Total Kelas</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalClasses }}</p>
                </x-card>
                <x-card>
                    <p class="text-sm text-gray-500">Total Mata Kuliah</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalCourses }}</p>
                </x-card>
            </div>

        </div>
    </div>
</x-app-layout>