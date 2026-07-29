<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Tahun Ajaran
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>

                @if (session('success'))
                    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
                @endif

                <x-button href="{{ route('academic-years.create') }}" class="mb-4"
                    icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>'>
                    Tambah Tahun Ajaran
                </x-button>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-2 font-medium text-gray-600">Tahun</th>
                            <th class="py-2 font-medium text-gray-600">Semester</th>
                            <th class="py-2 font-medium text-gray-600">Status</th>
                            <th class="py-2 font-medium text-gray-600 w-56">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($academicYears as $academicYear)
                            <tr class="border-b border-gray-50">
                                <td class="py-3 text-gray-800">{{ $academicYear->year }}</td>
                                <td class="py-3 text-gray-800 capitalize">{{ $academicYear->semester }}</td>
                                <td class="py-3">
                                    @if ($academicYear->is_active)
                                        <x-badge color="green">Aktif</x-badge>
                                    @else
                                        <x-badge color="gray">Nonaktif</x-badge>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <div class="flex items-center gap-2">
                                        <x-button href="{{ route('academic-years.edit', $academicYear) }}" variant="primary" size="sm"
                                            icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828H9V13z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19h14" /></svg>'>
                                            Edit
                                        </x-button>
                                        <form action="{{ route('academic-years.destroy', $academicYear) }}" method="POST" onsubmit="return confirm('Yakin hapus tahun ajaran ini?')">
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
                                <td colspan="4" class="py-6 text-gray-400 text-center">Belum ada data tahun ajaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $academicYears->links() }}
                </div>

            </x-card>
        </div>
    </div>
</x-app-layout>