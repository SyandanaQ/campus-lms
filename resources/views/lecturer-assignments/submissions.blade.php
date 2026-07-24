<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Submisi - {{ $assignment->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('assignments.index', $assignment->classRoom) }}" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke Daftar Tugas</a>
            </div>

            @forelse ($submissions as $submission)
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-medium text-gray-800">{{ $submission->student->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $submission->student->nim }}</p>
                            <p class="text-xs text-gray-400 mt-1">Disubmit: {{ $submission->submitted_at->format('d M Y, H:i') }}</p>
                        </div>
                        <a href="{{ Storage::url($submission->file_path) }}" target="_blank"
                           class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                            Lihat File
                        </a>
                    </div>

                    <form action="{{ route('submissions.grade', $submission) }}" method="POST" class="flex gap-4 items-end">
                        @csrf
                        @method('PUT')

                        <div class="flex-1">
                            <label class="block text-sm text-gray-700 mb-1">Nilai (0-100)</label>
                            <input type="number" name="score" value="{{ $submission->score }}" min="0" max="100"
                                   class="w-full border-gray-300 rounded shadow-sm">
                        </div>

                        <div class="flex-1">
                            <label class="block text-sm text-gray-700 mb-1">Feedback</label>
                            <input type="text" name="feedback" value="{{ $submission->feedback }}"
                                   class="w-full border-gray-300 rounded shadow-sm">
                        </div>

                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                            Simpan Nilai
                        </button>
                    </form>
                </div>
            @empty
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-500">
                    Belum ada mahasiswa yang submit tugas ini.
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>