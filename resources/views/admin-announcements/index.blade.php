<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pengumuman Global
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif

            <x-card>
                <h3 class="font-medium text-gray-800 mb-2">Buat Pengumuman Global</h3>
                <p class="text-sm text-gray-500 mb-4">Pengumuman ini akan terlihat oleh <strong>semua mahasiswa</strong>, tidak terikat kelas tertentu.</p>
                <form action="{{ route('admin-announcements.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <x-input name="title" label="Judul" />
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Isi Pengumuman</label>
                        <textarea name="body" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('body') }}</textarea>
                        @error('body')<p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <x-button>Publikasikan</x-button>
                </form>
            </x-card>

            <div class="space-y-4">
                @forelse ($announcements as $announcement)
                    <x-card>
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium text-gray-800">{{ $announcement->title }}</h4>
                                <p class="text-xs text-gray-400 mt-1">{{ $announcement->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <form action="{{ route('admin-announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Yakin hapus pengumuman ini?')">
                                @csrf
                                @method('DELETE')
                                <x-button variant="danger" size="sm">Hapus</x-button>
                            </form>
                        </div>
                        <p class="text-gray-600 mt-3 whitespace-pre-line">{{ $announcement->body }}</p>
                    </x-card>
                @empty
                    <x-card>
                        <p class="text-gray-400 text-center py-2">Belum ada pengumuman global.</p>
                    </x-card>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>