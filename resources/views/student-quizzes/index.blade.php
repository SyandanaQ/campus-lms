<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kuis - {{ $class->course->name }} (Kelas {{ $class->class_name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('error'))
                <x-alert type="error">{{ session('error') }}</x-alert>
            @endif

            <x-card>
                <a href="{{ route('enrollments.my') }}" class="text-blue-700 hover:underline text-sm font-medium">&larr; Kembali ke KRS Saya</a>
            </x-card>

            <x-card>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-2 font-medium text-gray-600">Judul Kuis</th>
                            <th class="py-2 font-medium text-gray-600">Jumlah Soal</th>
                            <th class="py-2 font-medium text-gray-600 w-48">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($quizzes as $quiz)
                            @php $attempt = $myAttempts->get($quiz->id); @endphp
                            <tr class="border-b border-gray-50">
                                <td class="py-3 text-gray-800">{{ $quiz->title }}</td>
                                <td class="py-3 text-gray-800">{{ $quiz->questions_count }}</td>
                                <td class="py-3">
                                    @if ($attempt)
                                        <x-button href="{{ route('student-quizzes.result', $quiz) }}" variant="outline" size="sm">
                                            Lihat Hasil (Skor: {{ $attempt->score }})
                                        </x-button>
                                    @else
                                        <x-button href="{{ route('student-quizzes.show', $quiz) }}" size="sm">
                                            Kerjakan
                                        </x-button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-6 text-gray-400 text-center">Belum ada kuis untuk kelas ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>

        </div>
    </div>
</x-app-layout>