<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pengumuman Global
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-gray-800 mb-4">Buat Pengumuman Global</h3>
                <p class="text-sm text-gray-500 mb-4">Pengumuman ini akan terlihat oleh <strong>semua mahasiswa</strong>, tidak terikat kelas tertentu.</p>
                <form action="{{ route('admin-announcements.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Judul</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="w-full border-gray-300 rounded shadow-sm">
                        @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Isi Pengumuman</label>
                        <textarea name="body" rows="4" class="w-full border-gray-300 rounded shadow-sm">{{ old('body') }}</textarea>
                        @error('body')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Publikasikan
                    </button>
                </form>
            </div>

            <div class="space-y-4">
                @forelse ($announcements as $announcement)
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium text-gray-800">{{ $announcement->title }}</h4>
                                <p class="text-xs text-gray-400 mt-1">{{ $announcement->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <form action="{{ route('admin-announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Yakin hapus pengumuman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                            </form>
                        </div>
                        <p class="text-gray-600 mt-3 whitespace-pre-line">{{ $announcement->body }}</p>
                    </div>
                @empty
                    <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-500">
                        Belum ada pengumuman global.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>