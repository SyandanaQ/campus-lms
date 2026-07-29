<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Kelas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>

                @if (session('success'))
                    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
                @endif

                @if (session('error'))
                    <x-alert type="error" class="mb-4">{{ session('error') }}</x-alert>
                @endif

                <x-button href="{{ route('classes.create') }}" class="mb-4"
                    icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>'>
                    Tambah Kelas
                </x-button>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-2 font-medium text-gray-600">Mata Kuliah</th>
                            <th class="py-2 font-medium text-gray-600">Kelas</th>
                            <th class="py-2 font-medium text-gray-600">Dosen</th>
                            <th class="py-2 font-medium text-gray-600">Tahun Ajaran</th>
                            <th class="py-2 font-medium text-gray-600">Kapasitas</th>
                            <th class="py-2 font-medium text-gray-600 w-56">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classes as $class)
                            <tr class="border-b border-gray-50">
                                <td class="py-3 text-gray-800">{{ $class->course->name }}</td>
                                <td class="py-3 text-gray-800">{{ $class->class_name }}</td>
                                <td class="py-3 text-gray-800">{{ $class->lecturer->user->name }}</td>
                                <td class="py-3 text-gray-800">{{ $class->academicYear->year }} ({{ ucfirst($class->academicYear->semester) }})</td>
                                <td class="py-3 text-gray-800">{{ $class->capacity }}</td>
                                <td class="py-3">
                                    <div class="flex items-center gap-2">
                                        <x-button href="{{ route('classes.edit', $class) }}" variant="primary" size="sm"
                                            icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828H9V13z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19h14" /></svg>'>
                                            Edit
                                        </x-button>
                                        <form action="{{ route('classes.destroy', $class) }}" method="POST" onsubmit="return confirm('Yakin hapus kelas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <x-button variant="danger" size="sm"
                                                icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>'>
                                                Hapus
                                            </x-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-gray-400 text-center">Belum ada data kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $classes->links() }}
                </div>

            </x-card>
        </div>
    </div>
</x-app-layout>