<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $thread->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('forum.index', $thread->classRoom) }}" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke Forum</a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-gray-800">{{ $thread->creator->name }}</p>
                        <p class="text-xs text-gray-400">{{ $thread->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if ($thread->user_id === Auth::id())
                        <form action="{{ route('forum.thread.destroy', $thread) }}" method="POST" onsubmit="return confirm('Yakin hapus thread ini? Semua komentar akan ikut terhapus.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm">Hapus Thread</button>
                        </form>
                    @endif
                </div>
                <p class="text-gray-700 mt-3 whitespace-pre-line">{{ $thread->body }}</p>
            </div>

            <div class="space-y-3">
                <h3 class="font-medium text-gray-800">Komentar ({{ $thread->comments->count() }})</h3>

                @forelse ($thread->comments as $comment)
                    <div class="bg-white shadow-sm sm:rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-800 text-sm">{{ $comment->creator->name }}</p>
                                <p class="text-xs text-gray-400">{{ $comment->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            @if ($comment->user_id === Auth::id())
                                <form action="{{ route('forum.comment.destroy', $comment) }}" method="POST" onsubmit="return confirm('Yakin hapus komentar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                                </form>
                            @endif
                        </div>
                        <p class="text-gray-700 mt-2 text-sm whitespace-pre-line">{{ $comment->body }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Belum ada komentar.</p>
                @endforelse
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('forum.comment', $thread) }}" method="POST">
                    @csrf
                    <textarea name="body" rows="3" placeholder="Tulis komentar..." class="w-full border-gray-300 rounded shadow-sm mb-3"></textarea>
                    @error('body')<p class="text-red-600 text-sm mb-2">{{ $message }}</p>@enderror
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                        Kirim Komentar
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>