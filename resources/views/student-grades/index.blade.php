<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nilai Saya
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-card>
                    <p class="text-sm text-gray-500">IPK (Indeks Prestasi Kumulatif)</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">
                        {{ $ipk ?? '-' }}
                    </p>
                </x-card>
                <x-card>
                    <p class="text-sm text-gray-500">Total SKS Ditempuh</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalSks }}</p>
                </x-card>
            </div>

            <x-card>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-2 font-medium text-gray-600">Mata Kuliah</th>
                            <th class="py-2 font-medium text-gray-600">SKS</th>
                            <th class="py-2 font-medium text-gray-600">Tahun Ajaran</th>
                            <th class="py-2 font-medium text-gray-600">Nilai</th>
                            <th class="py-2 font-medium text-gray-600">Huruf</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($grades as $grade)
                            <tr class="border-b border-gray-50">
                                <td class="py-3 text-gray-800">{{ $grade->classRoom->course->name }}</td>
                                <td class="py-3 text-gray-800">{{ $grade->classRoom->course->sks }}</td>
                                <td class="py-3 text-gray-800">{{ $grade->classRoom->academicYear->year }}</td>
                                <td class="py-3 text-gray-800">{{ $grade->score }}</td>
                                <td class="py-3">
                                    <x-badge color="blue">{{ $grade->letter }}</x-badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-gray-400 text-center">Belum ada nilai akhir yang tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>

        </div>
    </div>
</x-app-layout>