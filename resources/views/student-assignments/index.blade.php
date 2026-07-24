<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tugas - {{ $class->course->name }} (Kelas {{ $class->class_name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('enrollments.my') }}" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke KRS Saya</a>
            </div>

            @forelse ($assignments as $assignment)
                @php
                    $mySubmission = $mySubmissions->get($assignment->id);
                @endphp
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-medium text-gray-800">{{ $assignment->title }}</h3>
                            @if ($assignment->description)
                                <p class="text-sm text-gray-600 mt-1">{{ $assignment->description }}</p>
                            @endif
                            <p class="text-xs text-gray-500 mt-2">
                                Deadline: {{ $assignment->deadline->format('d M Y, H:i') }}
                                @if ($assignment->isPastDeadline())
                                    <span class="text-red-500">(Sudah Lewat)</span>
                                @endif
                                &middot; Bobot: {{ $assignment->weight }}%
                            </p>
                        </div>
                    </div>

                    @if ($mySubmission)
                        <div class="bg-gray-50 rounded p-3 mb-3 text-sm">
                            <p class="text-gray-600">Sudah disubmit: {{ $mySubmission->submitted_at->format('d M Y, H:i') }}</p>
                            <a href="{{ Storage::url($mySubmission->file_path) }}" target="_blank" class="text-blue-600 hover:underline">Lihat file yang disubmit</a>
                            @if ($mySubmission->score !== null)
                                <p class="mt-2"><strong>Nilai:</strong> {{ $mySubmission->score }}</p>
                                @if ($mySubmission->feedback)
                                    <p><strong>Feedback:</strong> {{ $mySubmission->feedback }}</p>
                                @endif
                            @else
                                <p class="mt-2 text-gray-400">Belum dinilai</p>
                            @endif
                        </div>
                    @endif

                    @if (!$assignment->isPastDeadline())
                        <form action="{{ route('student-assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data" class="flex gap-3 items-end">
                            @csrf
                            <div class="flex-1">
                                <label class="block text-sm text-gray-700 mb-1">
                                    {{ $mySubmission ? 'Ganti File (Submit Ulang)' : 'Upload File Tugas' }}
                                </label>
                                <input type="file" name="file" class="w-full border-gray-300 rounded shadow-sm text-sm">
                            </div>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                Submit
                            </button>
                        </form>
                    @else
                        @if (!$mySubmission)
                            <p class="text-red-500 text-sm">Deadline sudah lewat, tidak bisa submit.</p>
                        @endif
                    @endif
                </div>
            @empty
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-500">
                    Belum ada tugas untuk kelas ini.
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>