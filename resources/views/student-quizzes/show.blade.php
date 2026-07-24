<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if ($quiz->description)
                    <p class="text-gray-600 mb-6">{{ $quiz->description }}</p>
                @endif

                <form action="{{ route('student-quizzes.submit', $quiz) }}" method="POST">
                    @csrf

                    @foreach ($questions as $i => $question)
                        <div class="mb-6 pb-6 border-b last:border-b-0">
                            <p class="font-medium text-gray-800 mb-3">{{ $i + 1 }}. {{ $question->question_text }}</p>
                            @foreach ($question->options as $option)
                                <label class="flex items-center gap-2 mb-2 text-sm text-gray-700">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" required>
                                    {{ $option->option_text }}
                                </label>
                            @endforeach
                        </div>
                    @endforeach

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Submit Kuis
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>