<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelola Soal - {{ $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif

            <x-card>
                <a href="{{ route('quizzes.index', $quiz->classRoom) }}" class="text-blue-700 hover:underline text-sm font-medium">&larr; Kembali ke Daftar Kuis</a>
            </x-card>

            @if ($isLocked)
                <x-alert type="warning">
                    <strong>Kuis ini terkunci.</strong> Sudah ada mahasiswa yang mengerjakan, sehingga soal tidak bisa ditambah atau dihapus lagi untuk menjaga keadilan penilaian.
                </x-alert>
            @else
            <x-card x-data="{ options: ['', '', '', ''], correct: 0 }">
                <h3 class="font-medium text-gray-800 mb-4">Tambah Soal</h3>

                <form action="{{ route('quiz-questions.store', $quiz) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Pertanyaan</label>
                        <textarea name="question_text" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        @error('question_text')<p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan Jawaban (pilih radio untuk jawaban benar)</label>

                    <template x-for="(option, index) in options" :key="index">
                        <div class="mb-2 flex items-center gap-3">
                            <input type="radio" name="correct_option" :value="index" x-model="correct" class="text-blue-700 focus:ring-blue-500">
                            <input type="text" :name="'options[' + index + ']'" x-model="options[index]"
                                   class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Teks pilihan jawaban">
                            <button type="button" @click="options.splice(index, 1)" x-show="options.length > 2"
                                    class="text-red-500 hover:text-red-700 text-sm">
                                Hapus
                            </button>
                        </div>
                    </template>

                    <button type="button" @click="options.push('')" class="text-sm text-blue-700 hover:underline mb-4">
                        + Tambah Pilihan
                    </button>

                    @if ($errors->has('options.*') || $errors->has('question_text'))
                        <div class="mb-3 p-3 bg-red-100 text-red-700 text-sm rounded-lg">
                            <p>Mohon lengkapi semua pilihan jawaban dan pertanyaan.</p>
                        </div>
                    @endif

                    <div>
                        <x-button icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>'>
                            Simpan Soal
                        </x-button>
                    </div>
                </form>
            </x-card>
            @endif

            <x-card>
                <h3 class="font-medium text-gray-800 mb-4">Daftar Soal ({{ $questions->count() }})</h3>

                @forelse ($questions as $i => $question)
                    <div class="mb-4 pb-4 border-b border-gray-50 last:border-b-0">
                        <div class="flex justify-between items-start">
                            <p class="font-medium text-gray-800">{{ $i + 1 }}. {{ $question->question_text }}</p>
                            @if (!$isLocked)
                                <form action="{{ route('quiz-questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Yakin hapus soal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" size="sm">Hapus</x-button>
                                </form>
                            @endif
                        </div>
                        <ul class="mt-2 ml-4 text-sm space-y-1">
                            @foreach ($question->options as $option)
                                <li class="{{ $option->is_correct ? 'text-green-600 font-medium' : 'text-gray-600' }}">
                                    {{ $option->option_text }} @if($option->is_correct) (Benar) @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-2">Belum ada soal.</p>
                @endforelse
            </x-card>

        </div>
    </div>
</x-app-layout>