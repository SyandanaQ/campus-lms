<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($activeYear)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-800">
                    Tahun Ajaran Aktif: <strong>{{ $activeYear->year }} ({{ ucfirst($activeYear->semester) }})</strong>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800">
                    Belum ada tahun ajaran aktif.
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total Mahasiswa</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalStudents }}</p>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total Dosen</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalLecturers }}</p>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total Kelas</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalClasses }}</p>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total Mata Kuliah</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalCourses }}</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>