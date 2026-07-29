<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tugas - {{ $class->course->name }} (Kelas {{ $class->class_name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif

            @if (session('error'))
                <x-alert type="error">{{ session('error') }}</x-alert>
            @endif

            <x-card>
                <a href="{{ route('enrollments.my') }}" class="text-blue-700 hover:underline text-sm font-medium">&larr; Kembali ke KRS Saya</a>
            </x-card>

            @forelse ($assignments as $assignment)
                @php
                    $mySubmission = $mySubmissions->get($assignment->id);
                @endphp
                <x-card>
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-medium text-gray-800">{{ $assignment->title }}</h3>
                            @if ($assignment->description)
                                <p class="text-sm text-gray-600 mt-1">{{ $assignment->description }}</p>
                            @endif
                            <p class="text-xs text-gray-500 mt-2">
                                Deadline: {{ $assignment->deadline->format('d M Y, H:i') }}
                                @if ($assignment->isPastDeadline())
                                    <x-badge color="red">Sudah Lewat</x-badge>
                                @endif
                                &middot; Bobot: {{ $assignment->weight }}%
                            </p>
                        </div>
                    </div>

                    @if ($mySubmission)
                        <div class="bg-gray-50 rounded-lg p-4 mb-3 text-sm">
                            <p class="text-gray-600">Sudah disubmit: {{ $mySubmission->submitted_at->format('d M Y, H:i') }}</p>
                            <a href="{{ Storage::url($mySubmission->file_path) }}" target="_blank" class="text-blue-700 hover:underline">Lihat file yang disubmit</a>
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
                                <label class="block text-sm text-gray-700 mb-1.5">
                                    {{ $mySubmission ? 'Ganti File (Submit Ulang)' : 'Upload File Tugas' }}
                                </label>
                                <input type="file" name="file" class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                            </div>
                            <x-button size="sm">Submit</x-button>
                        </form>
                    @else
                        @if (!$mySubmission)
                            <p class="text-red-500 text-sm">Deadline sudah lewat, tidak bisa submit.</p>
                        @endif
                    @endif
                </x-card>
            @empty
                <x-card>
                    <p class="text-gray-400 text-center py-2">Belum ada tugas untuk kelas ini.</p>
                </x-card>
            @endforelse

        </div>
    </div>
</x-app-layout>