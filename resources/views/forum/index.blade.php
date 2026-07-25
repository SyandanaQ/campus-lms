<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Forum Diskusi - {{ $class->course->name }} (Kelas {{ $class->class_name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-gray-800 mb-4">Buat Thread Baru</h3>
                <form action="{{ route('forum.store', $class) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Judul</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="w-full border-gray-300 rounded shadow-sm">
                        @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Pertanyaan / Topik</label>
                        <textarea name="body" rows="3" class="w-full border-gray-300 rounded shadow-sm">{{ old('body') }}</textarea>
                        @error('body')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Buat Thread
                    </button>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-gray-800 mb-4">Diskusi</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Judul</th>
                            <th class="py-2">Dibuat oleh</th>
                            <th class="py-2">Komentar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($threads as $thread)
                            <tr class="border-b">
                                <td class="py-2">
                                    <a href="{{ route('forum.show', $thread) }}" class="text-blue-600 hover:underline">{{ $thread->title }}</a>
                                </td>
                                <td class="py-2 text-sm">{{ $thread->creator->name }}</td>
                                <td class="py-2 text-sm">{{ $thread->comments_count }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-4 text-gray-500">Belum ada diskusi. Jadilah yang pertama!</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>