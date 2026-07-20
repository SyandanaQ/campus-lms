<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Program Studi
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

                <a href="{{ route('study-programs.create') }}" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Tambah Program Studi
                </a>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Nama Prodi</th>
                            <th class="py-2">Fakultas</th>
                            <th class="py-2">Jenjang</th>
                            <th class="py-2 w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($studyPrograms as $studyProgram)
                            <tr class="border-b">
                                <td class="py-2">{{ $studyProgram->name }}</td>
                                <td class="py-2">{{ $studyProgram->faculty->name }}</td>
                                <td class="py-2">{{ $studyProgram->level }}</td>
                                <td class="py-2">
                                    <a href="{{ route('study-programs.edit', $studyProgram) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('study-programs.destroy', $studyProgram) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus program studi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline ml-2">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-gray-500">Belum ada data program studi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $studyPrograms->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>