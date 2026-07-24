<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kuis - {{ $class->course->name }} (Kelas {{ $class->class_name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('enrollments.my') }}" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke KRS Saya</a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Judul Kuis</th>
                            <th class="py-2">Jumlah Soal</th>
                            <th class="py-2 w-40">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($quizzes as $quiz)
                            @php $attempt = $myAttempts->get($quiz->id); @endphp
                            <tr class="border-b">
                                <td class="py-2">{{ $quiz->title }}</td>
                                <td class="py-2">{{ $quiz->questions_count }}</td>
                                <td class="py-2">
                                    @if ($attempt)
                                        <a href="{{ route('student-quizzes.result', $quiz) }}" class="text-blue-600 hover:underline text-sm">
                                            Lihat Hasil (Skor: {{ $attempt->score }})
                                        </a>
                                    @else
                                        <a href="{{ route('student-quizzes.show', $quiz) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                            Kerjakan
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-4 text-gray-500">Belum ada kuis untuk kelas ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>