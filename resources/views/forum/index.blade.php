<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Forum Diskusi - {{ $class->course->name }} (Kelas {{ $class->class_name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif

            <x-card>
                <h3 class="font-medium text-gray-800 mb-4">Buat Thread Baru</h3>
                <form action="{{ route('forum.store', $class) }}" method="POST" class="space-y-5">
                    @csrf
                    <x-input name="title" label="Judul" />
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Pertanyaan / Topik</label>
                        <textarea name="body" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('body') }}</textarea>
                        @error('body')<p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <x-button icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>'>
                        Buat Thread
                    </x-button>
                </form>
            </x-card>

            <x-card>
                <h3 class="font-medium text-gray-800 mb-4">Diskusi</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-2 font-medium text-gray-600">Judul</th>
                            <th class="py-2 font-medium text-gray-600">Dibuat oleh</th>
                            <th class="py-2 font-medium text-gray-600">Komentar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($threads as $thread)
                            <tr class="border-b border-gray-50">
                                <td class="py-3">
                                    <a href="{{ route('forum.show', $thread) }}" class="text-blue-700 hover:underline font-medium">{{ $thread->title }}</a>
                                </td>
                                <td class="py-3 text-sm text-gray-600">{{ $thread->creator->name }}</td>
                                <td class="py-3 text-sm text-gray-600">{{ $thread->comments_count }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-6 text-gray-400 text-center">Belum ada diskusi. Jadilah yang pertama!</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>

        </div>
    </div>
</x-app-layout>