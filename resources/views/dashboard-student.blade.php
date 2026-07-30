<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Mahasiswa
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <x-card>
                    <p class="text-sm text-gray-500">Kelas Diambil</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $classCount }}</p>
                </x-card>
                <x-card>
                    <p class="text-sm text-gray-500">Tugas Selesai</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $assignmentSubmitted }} / {{ $assignmentTotal }}</p>
                </x-card>
                <x-card>
                    <p class="text-sm text-gray-500">Kuis Dikerjakan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $quizDone }} / {{ $quizTotal }}</p>
                </x-card>
            </div>

            <x-card>
                <p class="text-sm text-gray-500">Rata-rata Nilai Akhir</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">
                    {{ $averageScore ?? 'Belum ada nilai' }}
                </p>
            </x-card>

        </div>
    </div>
</x-app-layout>