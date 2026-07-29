<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Submisi - {{ $assignment->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif

            <x-card>
                <a href="{{ route('assignments.index', $assignment->classRoom) }}" class="text-blue-700 hover:underline text-sm font-medium">&larr; Kembali ke Daftar Tugas</a>
            </x-card>

            @forelse ($submissions as $submission)
                <x-card>
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-medium text-gray-800">{{ $submission->student->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $submission->student->nim }}</p>
                            <p class="text-xs text-gray-400 mt-1">Disubmit: {{ $submission->submitted_at->format('d M Y, H:i') }}</p>
                        </div>
                        <x-button href="{{ Storage::url($submission->file_path) }}" variant="outline" size="sm" target="_blank">
                            Lihat File
                        </x-button>
                    </div>

                    <form action="{{ route('submissions.grade', $submission) }}" method="POST" class="flex gap-4 items-end">
                        @csrf
                        @method('PUT')

                        <div class="flex-1">
                            <label class="block text-sm text-gray-700 mb-1.5">Nilai (0-100)</label>
                            <input type="number" name="score" value="{{ $submission->score }}" min="0" max="100"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div class="flex-1">
                            <label class="block text-sm text-gray-700 mb-1.5">Feedback</label>
                            <input type="text" name="feedback" value="{{ $submission->feedback }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <x-button variant="primary" size="sm">Simpan Nilai</x-button>
                    </form>
                </x-card>
            @empty
                <x-card>
                    <p class="text-gray-400 text-center py-2">Belum ada mahasiswa yang submit tugas ini.</p>
                </x-card>
            @endforelse

        </div>
    </div>
</x-app-layout>