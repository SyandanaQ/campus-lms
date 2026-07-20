<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Tahun Ajaran
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('academic-years.create') }}" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Tambah Tahun Ajaran
                </a>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Tahun</th>
                            <th class="py-2">Semester</th>
                            <th class="py-2">Status</th>
                            <th class="py-2 w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($academicYears as $academicYear)
                            <tr class="border-b">
                                <td class="py-2">{{ $academicYear->year }}</td>
                                <td class="py-2 capitalize">{{ $academicYear->semester }}</td>
                                <td class="py-2">
                                    @if ($academicYear->is_active)
                                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">Aktif</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    <a href="{{ route('academic-years.edit', $academicYear) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('academic-years.destroy', $academicYear) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus tahun ajaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline ml-2">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-gray-500">Belum ada data tahun ajaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $academicYears->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>