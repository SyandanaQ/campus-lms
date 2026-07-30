<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Dosen
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @forelse ($classSummaries as $summary)
                <x-card>
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-medium text-gray-800">{{ $summary['class']->course->name }}</h3>
                            <p class="text-sm text-gray-500">Kelas {{ $summary['class']->class_name }}</p>
                        </div>
                        <x-button href="{{ route('lecturer-classes.show', $summary['class']) }}" variant="outline" size="sm">
                            Lihat Detail
                        </x-button>
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
                </x-card>
            @empty
                <x-card>
                    <p class="text-gray-400 text-center py-2">Anda belum mengampu kelas apapun.</p>
                </x-card>
            @endforelse

        </div>
    </div>
</x-app-layout>