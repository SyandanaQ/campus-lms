<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Dosen
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

<div class="flex items-center gap-2 mb-4">
                    <x-button href="{{ route('lecturers.create') }}"
                        icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>'>
                        Tambah Dosen
                    </x-button>
                    <x-button href="{{ route('lecturers.import') }}" variant="secondary">
                        Import CSV
                    </x-button>
                </div>

                @if (session('import_errors') && count(session('import_errors')) > 0)
                    <x-alert type="warning" class="mb-4">
                        <strong>Detail baris yang gagal:</strong>
                        <ul class="list-disc ml-5 mt-1">
                            @foreach (session('import_errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                @endif
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-2 font-medium text-gray-600">Nama</th>
                            <th class="py-2 font-medium text-gray-600">Email</th>
                            <th class="py-2 font-medium text-gray-600">NIDN</th>
                            <th class="py-2 font-medium text-gray-600">Program Studi</th>
                            <th class="py-2 font-medium text-gray-600 w-56">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lecturers as $lecturer)
                            <tr class="border-b border-gray-50">
                                <td class="py-3 text-gray-800">{{ $lecturer->user->name }}</td>
                                <td class="py-3 text-gray-800">{{ $lecturer->user->email }}</td>
                                <td class="py-3 text-gray-800">{{ $lecturer->nidn }}</td>
                                <td class="py-3 text-gray-800">{{ $lecturer->studyProgram->name }}</td>
                                <td class="py-3">
                                    <div class="flex items-center gap-2">
                                        <x-button href="{{ route('lecturers.edit', $lecturer) }}" variant="primary" size="sm"
                                            icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828H9V13z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19h14" /></svg>'>
                                            Edit
                                        </x-button>
                                        <form action="{{ route('lecturers.destroy', $lecturer) }}" method="POST" onsubmit="return confirm('Yakin hapus dosen ini?')">
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
                                <td colspan="5" class="py-6 text-gray-400 text-center">Belum ada data dosen.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $lecturers->links() }}
                </div>

            </x-card>
        </div>
    </div>
</x-app-layout>