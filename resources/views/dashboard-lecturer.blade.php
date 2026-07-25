<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Dosen
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @forelse ($classSummaries as $summary)
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-medium text-gray-800">{{ $summary['class']->course->name }}</h3>
                            <p class="text-sm text-gray-500">Kelas {{ $summary['class']->class_name }}</p>
                        </div>
                        <a href="{{ route('lecturer-classes.show', $summary['class']) }}" class="text-blue-600 hover:underline text-sm">
                            Lihat Detail &rarr;
                        </a>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Mahasiswa</p>
                            <p class="text-xl font-bold text-gray-800">{{ $summary['studentCount'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Belum Dinilai</p>
                            <p class="text-xl font-bold {{ $summary['ungradedCount'] > 0 ? 'text-red-600' : 'text-gray-800' }}">
                                {{ $summary['ungradedCount'] }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Materi</p>
                            <p class="text-xl font-bold text-gray-800">{{ $summary['materialCount'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Kuis</p>
                            <p class="text-xl font-bold text-gray-800">{{ $summary['quizCount'] }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white shadow-sm rounded-lg p-6 text-gray-500">
                    Anda belum mengampu kelas apapun.
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>