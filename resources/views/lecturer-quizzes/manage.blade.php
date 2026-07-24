<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelola Soal - {{ $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('quizzes.index', $quiz->classRoom) }}" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke Daftar Kuis</a>
            </div>

            @if ($isLocked)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-yellow-800 text-sm">
                    <strong>Kuis ini terkunci.</strong> Sudah ada mahasiswa yang mengerjakan, sehingga soal tidak bisa ditambah atau dihapus lagi untuk menjaga keadilan penilaian.
                </div>
            @else
            <!-- Form Tambah Soal -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6" x-data="{ options: ['', '', '', ''], correct: 0 }">
                <h3 class="font-medium text-gray-800 mb-4">Tambah Soal</h3>

                <form action="{{ route('quiz-questions.store', $quiz) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Pertanyaan</label>
                        <textarea name="question_text" rows="2" class="w-full border-gray-300 rounded shadow-sm"></textarea>
                        @error('question_text')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <label class="block font-medium text-sm text-gray-700 mb-2">Pilihan Jawaban (pilih radio untuk jawaban benar)</label>

<template x-for="(option, index) in options" :key="index">
                        <div class="mb-2 flex items-center gap-3">
                            <input type="radio" name="correct_option" :value="index" x-model="correct">
                            <input type="text" :name="'options[' + index + ']'" x-model="options[index]"
                                   class="flex-1 border-gray-300 rounded shadow-sm" placeholder="Teks pilihan jawaban">
                            <button type="button" @click="options.splice(index, 1)" x-show="options.length > 2"
                                    class="text-red-500 hover:text-red-700 text-sm">
                                Hapus
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="options.push('')" class="text-sm text-blue-600 hover:underline mb-4">
                        + Tambah Pilihan
                    </button>

@if ($errors->has('options.*') || $errors->has('question_text'))
                        <div class="mb-3 p-3 bg-red-100 text-red-700 text-sm rounded">
                            <p>Mohon lengkapi semua pilihan jawaban dan pertanyaan.</p>
                            <ul class="list-disc ml-5 mt-1">
                                @foreach ($errors->get('options.*') as $errorGroup)
                                    @foreach ($errorGroup as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                @endforeach
                                @error('question_text')<li>{{ $message }}</li>@enderror
                            </ul>
                        </div>
                    @endif
                    <div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Simpan Soal
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <!-- Daftar Soal -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-gray-800 mb-4">Daftar Soal ({{ $questions->count() }})</h3>

                @forelse ($questions as $i => $question)
                    <div class="mb-4 pb-4 border-b last:border-b-0">
                        <div class="flex justify-between items-start">
                            <p class="font-medium text-gray-800">{{ $i + 1 }}. {{ $question->question_text }}</p>
                            @if (!$isLocked)
                                <form action="{{ route('quiz-questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Yakin hapus soal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                                </form>
                            @endif
                        </div>
                        <ul class="mt-2 ml-4 text-sm">
                            @foreach ($question->options as $option)
                                <li class="{{ $option->is_correct ? 'text-green-600 font-medium' : 'text-gray-600' }}">
                                    {{ $option->option_text }} @if($option->is_correct) (Benar) @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @empty
                    <p class="text-gray-500">Belum ada soal.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>