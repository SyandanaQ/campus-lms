<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kuis - {{ $class->course->name }} (Kelas {{ $class->class_name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif

            <x-card>
                <a href="{{ route('lecturer-classes.show', $class) }}" class="text-blue-700 hover:underline text-sm font-medium">&larr; Kembali ke Detail Kelas</a>
            </x-card>

            <x-card>
                <h3 class="font-medium text-gray-800 mb-4">Buat Kuis Baru</h3>
                <form action="{{ route('quizzes.store', $class) }}" method="POST" class="space-y-5">
                    @csrf
                    <x-input name="title" label="Judul Kuis" />
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                        <textarea name="description" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                    </div>
                    <x-button icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>'>
                        Buat Kuis
                    </x-button>
                </form>
            </x-card>

            <x-card>
                <h3 class="font-medium text-gray-800 mb-4">Daftar Kuis</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-2 font-medium text-gray-600">Judul</th>
                            <th class="py-2 font-medium text-gray-600">Jumlah Soal</th>
                            <th class="py-2 font-medium text-gray-600 w-56">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($quizzes as $quiz)
                            <tr class="border-b border-gray-50">
                                <td class="py-3 text-gray-800">{{ $quiz->title }}</td>
                                <td class="py-3 text-gray-800">{{ $quiz->questions_count }}</td>
                                <td class="py-3">
                                    <div class="flex items-center gap-2">
                                        <x-button href="{{ route('quizzes.manage', $quiz) }}" variant="outline" size="sm">Kelola Soal</x-button>
                                        <x-button href="{{ route('quizzes.results', $quiz) }}" variant="outline" size="sm">Lihat Hasil</x-button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-6 text-gray-400 text-center">Belum ada kuis.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>

        </div>
    </div>
</x-app-layout>