<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Materi - {{ $class->course->name }} (Kelas {{ $class->class_name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('lecturer-classes.show', $class) }}" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke Detail Kelas</a>
            </div>

            <!-- Form Tambah Materi -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6" x-data="{ type: 'pdf' }">
                <h3 class="font-medium text-gray-800 mb-4">Tambah Materi Baru</h3>

                <form action="{{ route('materials.store', $class) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Judul Materi</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="w-full border-gray-300 rounded shadow-sm">
                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Tipe Materi</label>
                        <select name="type" x-model="type" class="w-full border-gray-300 rounded shadow-sm">
                            <option value="pdf">File PDF</option>
                            <option value="youtube">Video YouTube</option>
                            <option value="link">Link Lainnya</option>
                        </select>
                        @error('type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4" x-show="type === 'pdf'">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Upload File PDF (max 10MB)</label>
                        <input type="file" name="file" accept="application/pdf"
                               class="w-full border-gray-300 rounded shadow-sm">
                        @error('file')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4" x-show="type === 'youtube' || type === 'link'">
                        <label class="block font-medium text-sm text-gray-700 mb-1">URL</label>
                        <input type="text" name="url" value="{{ old('url') }}" placeholder="https://..."
                               class="w-full border-gray-300 rounded shadow-sm">
                        @error('url')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Tambah Materi
                    </button>
                </form>
            </div>

            <!-- Daftar Materi -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-gray-800 mb-4">Daftar Materi</h3>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Judul</th>
                            <th class="py-2">Tipe</th>
                            <th class="py-2 w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($materials as $material)
                            <tr class="border-b">
                                <td class="py-2">{{ $material->title }}</td>
                                <td class="py-2 uppercase text-xs">{{ $material->type }}</td>
                                <td class="py-2">
                                    <form action="{{ route('materials.destroy', $material) }}" method="POST" onsubmit="return confirm('Yakin hapus materi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-gray-500">Belum ada materi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>