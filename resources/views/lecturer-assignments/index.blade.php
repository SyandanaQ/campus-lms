<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tugas - {{ $class->course->name }} (Kelas {{ $class->class_name }})
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

            <!-- Form Buat Tugas -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-gray-800 mb-4">Buat Tugas Baru</h3>

                <form action="{{ route('assignments.store', $class) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Judul Tugas</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="w-full border-gray-300 rounded shadow-sm">
                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full border-gray-300 rounded shadow-sm">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Deadline</label>
                        <input type="datetime-local" name="deadline" value="{{ old('deadline') }}"
                               class="w-full border-gray-300 rounded shadow-sm">
                        @error('deadline')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Bobot Nilai (%)</label>
                        <input type="number" name="weight" value="{{ old('weight', 10) }}" min="0" max="100"
                               class="w-full border-gray-300 rounded shadow-sm">
                        @error('weight')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Buat Tugas
                    </button>
                </form>
            </div>

            <!-- Daftar Tugas -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-gray-800 mb-4">Daftar Tugas</h3>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Judul</th>
                            <th class="py-2">Deadline</th>
                            <th class="py-2">Bobot</th>
                            <th class="py-2 w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assignments as $assignment)
                            <tr class="border-b">
                                <td class="py-2">{{ $assignment->title }}</td>
                                <td class="py-2 text-sm">
                                    {{ $assignment->deadline->format('d M Y, H:i') }}
                                    @if ($assignment->isPastDeadline())
                                        <span class="text-red-500 text-xs">(Lewat)</span>
                                    @endif
                                </td>
                                <td class="py-2">{{ $assignment->weight }}%</td>
                                <td class="py-2">
                                    <a href="{{ route('assignments.submissions', $assignment) }}" class="text-blue-600 hover:underline text-sm">Lihat Submisi</a>
                                    <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus tugas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm ml-2">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-gray-500">Belum ada tugas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>