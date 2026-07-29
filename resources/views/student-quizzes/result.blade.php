<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Hasil Kuis - {{ $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <x-card>
                <a href="{{ route('student-quizzes.index', $quiz->classRoom) }}" class="text-blue-700 hover:underline text-sm font-medium">&larr; Kembali ke Daftar Kuis</a>
            </x-card>

            @if (session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif

            @if (session('error'))
                <x-alert type="warning">{{ session('error') }}</x-alert>
            @endif

            <x-card>
                <p class="text-lg font-medium text-gray-800">Skor Anda: {{ $attempt->score }}</p>
                <p class="text-sm text-gray-500">Disubmit: {{ $attempt->submitted_at->format('d M Y, H:i') }}</p>
            </x-card>

            <x-card>
                @foreach ($attempt->answers as $i => $answer)
                    <div class="mb-4 pb-4 border-b border-gray-50 last:border-b-0">
                        <p class="font-medium text-gray-800">{{ $i + 1 }}. {{ $answer->question->question_text }}</p>
                        <p class="text-sm mt-1 {{ $answer->option->is_correct ? 'text-green-600' : 'text-red-600' }}">
                            Jawaban Anda: {{ $answer->option->option_text }}
                            {{ $answer->option->is_correct ? '(Benar)' : '(Salah)' }}
                        </p>
                    </div>
                @endforeach
            </x-card>

        </div>
    </div>
</x-app-layout>