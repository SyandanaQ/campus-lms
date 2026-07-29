<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Materi - {{ $class->course->name }} (Kelas {{ $class->class_name }})
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

            <!-- Form Tambah Materi -->
            <x-card x-data="{ type: 'pdf' }">
                <h3 class="font-medium text-gray-800 mb-4">Tambah Materi Baru</h3>

                <form action="{{ route('materials.store', $class) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <x-input name="title" label="Judul Materi" />

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Materi</label>
                        <select name="type" x-model="type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="pdf">File PDF</option>
                            <option value="youtube">Video YouTube</option>
                            <option value="link">Link Lainnya</option>
                        </select>
                        @error('type')
                            <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="type === 'pdf'">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Upload File PDF (max 10MB)</label>
                        <input type="file" name="file" accept="application/pdf"
                               class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                        @error('file')
                            <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="type === 'youtube' || type === 'link'">
                        <x-input name="url" label="URL" />
                    </div>

                    <x-button icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>'>
                        Tambah Materi
                    </x-button>
                </form>
            </x-card>

            <!-- Daftar Materi -->
            <x-card>
                <h3 class="font-medium text-gray-800 mb-4">Daftar Materi</h3>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-2 font-medium text-gray-600">Judul</th>
                            <th class="py-2 font-medium text-gray-600">Tipe</th>
                            <th class="py-2 font-medium text-gray-600 w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($materials as $material)
                            <tr class="border-b border-gray-50">
                                <td class="py-3 text-gray-800">{{ $material->title }}</td>
                                <td class="py-3">
                                    <x-badge color="blue">{{ strtoupper($material->type) }}</x-badge>
                                </td>
                                <td class="py-3">
                                    <form action="{{ route('materials.destroy', $material) }}" method="POST" onsubmit="return confirm('Yakin hapus materi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-button variant="danger" size="sm"
                                            icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>'>
                                            Hapus
                                        </x-button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-gray-400 text-center">Belum ada materi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>

        </div>
    </div>
</x-app-layout>