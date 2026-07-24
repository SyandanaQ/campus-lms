<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kuis - {{ $class->course->name }} (Kelas {{ $class->class_name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('lecturer-classes.show', $class) }}" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke Detail Kelas</a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-gray-800 mb-4">Buat Kuis Baru</h3>
                <form action="{{ route('quizzes.store', $class) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Judul Kuis</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="w-full border-gray-300 rounded shadow-sm">
                        @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="2" class="w-full border-gray-300 rounded shadow-sm">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Buat Kuis</button>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-gray-800 mb-4">Daftar Kuis</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Judul</th>
                            <th class="py-2">Jumlah Soal</th>
                            <th class="py-2 w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($quizzes as $quiz)
                            <tr class="border-b">
                                <td class="py-2">{{ $quiz->title }}</td>
                                <td class="py-2">{{ $quiz->questions_count }}</td>
                                <td class="py-2">
                                    <a href="{{ route('quizzes.manage', $quiz) }}" class="text-blue-600 hover:underline text-sm">Kelola Soal</a>
                                    <a href="{{ route('quizzes.results', $quiz) }}" class="text-blue-600 hover:underline text-sm ml-2">Lihat Hasil</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-4 text-gray-500">Belum ada kuis.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>