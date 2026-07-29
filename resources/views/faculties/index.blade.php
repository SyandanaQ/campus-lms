<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Fakultas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>

                @if (session('success'))
                    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
                @endif

<x-button href="{{ route('faculties.create') }}" class="mb-4"
                    icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>'>
                    Tambah Fakultas
                </x-button>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-2 font-medium text-gray-600">Nama Fakultas</th>
                            <th class="py-2 font-medium text-gray-600 w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($faculties as $faculty)
                            <tr class="border-b border-gray-50">
                                <td class="py-3 text-gray-800">{{ $faculty->name }}</td>
                                <td class="py-3">
                                    <div class="flex items-center gap-2">
                                        <x-button href="{{ route('faculties.edit', $faculty) }}" variant="primary" size="sm"
                                            icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828H9V13z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19h14" /></svg>'>
                                            Edit
                                        </x-button>
                                        <form action="{{ route('faculties.destroy', $faculty) }}" method="POST" onsubmit="return confirm('Yakin hapus fakultas ini?')">
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
                                <td colspan="2" class="py-6 text-gray-400 text-center">Belum ada data fakultas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $faculties->links() }}
                </div>

            </x-card>
        </div>
    </div>
</x-app-layout>