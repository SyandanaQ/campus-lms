<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tugas - {{ $class->course->name }} (Kelas {{ $class->class_name }})
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

            <x-card>
                <h3 class="font-medium text-gray-800 mb-4">Buat Tugas Baru</h3>

                <form action="{{ route('assignments.store', $class) }}" method="POST" class="space-y-5">
                    @csrf

                    <x-input name="title" label="Judul Tugas" />

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-input name="deadline" label="Deadline" type="datetime-local" />
                    <x-input name="weight" label="Bobot Nilai (%)" type="number" :value="10" />

                    <x-button icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>'>
                        Buat Tugas
                    </x-button>
                </form>
            </x-card>

            <x-card>
                <h3 class="font-medium text-gray-800 mb-4">Daftar Tugas</h3>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-2 font-medium text-gray-600">Judul</th>
                            <th class="py-2 font-medium text-gray-600">Deadline</th>
                            <th class="py-2 font-medium text-gray-600">Bobot</th>
                            <th class="py-2 font-medium text-gray-600 w-56">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assignments as $assignment)
                            <tr class="border-b border-gray-50">
                                <td class="py-3 text-gray-800">{{ $assignment->title }}</td>
                                <td class="py-3 text-sm">
                                    <span class="text-gray-800">{{ $assignment->deadline->format('d M Y, H:i') }}</span>
                                    @if ($assignment->isPastDeadline())
                                        <x-badge color="red" class="ml-1">Lewat</x-badge>
                                    @endif
                                </td>
                                <td class="py-3 text-gray-800">{{ $assignment->weight }}%</td>
                                <td class="py-3">
                                    <div class="flex items-center gap-2">
                                        <x-button href="{{ route('assignments.submissions', $assignment) }}" variant="outline" size="sm">Lihat Submisi</x-button>
                                        <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Yakin hapus tugas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <x-button variant="danger" size="sm">Hapus</x-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-gray-400 text-center">Belum ada tugas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>

        </div>
    </div>
</x-app-layout>