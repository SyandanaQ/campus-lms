<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $thread->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif

            <x-card>
                <a href="{{ route('forum.index', $thread->classRoom) }}" class="text-blue-700 hover:underline text-sm font-medium">&larr; Kembali ke Forum</a>
            </x-card>

            <x-card>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-gray-800">{{ $thread->creator->name }}</p>
                        <p class="text-xs text-gray-400">{{ $thread->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if ($thread->user_id === Auth::id())
                        <form action="{{ route('forum.thread.destroy', $thread) }}" method="POST" onsubmit="return confirm('Yakin hapus thread ini? Semua komentar akan ikut terhapus.')">
                            @csrf
                            @method('DELETE')
                            <x-button variant="danger" size="sm">Hapus Thread</x-button>
                        </form>
                    @endif
                </div>
                <p class="text-gray-700 mt-3 whitespace-pre-line">{{ $thread->body }}</p>
            </x-card>

            <div class="space-y-3">
                <h3 class="font-medium text-gray-800">Komentar ({{ $thread->comments->count() }})</h3>

                @forelse ($thread->comments as $comment)
                    <x-card>
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-800 text-sm">{{ $comment->creator->name }}</p>
                                <p class="text-xs text-gray-400">{{ $comment->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            @if ($comment->user_id === Auth::id())
                                <form action="{{ route('forum.comment.destroy', $comment) }}" method="POST" onsubmit="return confirm('Yakin hapus komentar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" size="sm">Hapus</x-button>
                                </form>
                            @endif
                        </div>
                        <p class="text-gray-700 mt-2 text-sm whitespace-pre-line">{{ $comment->body }}</p>
                    </x-card>
                @empty
                    <p class="text-gray-400 text-sm">Belum ada komentar.</p>
                @endforelse
            </div>

            <x-card>
                <form action="{{ route('forum.comment', $thread) }}" method="POST">
                    @csrf
                    <textarea name="body" rows="3" placeholder="Tulis komentar..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 mb-3"></textarea>
                    @error('body')<p class="text-red-600 text-sm mb-2">{{ $message }}</p>@enderror
                    <x-button size="sm">Kirim Komentar</x-button>
                </form>
            </x-card>

        </div>
    </div>
</x-app-layout>