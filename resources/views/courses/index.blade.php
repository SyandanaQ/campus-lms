<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Mata Kuliah
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('courses.create') }}" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Tambah Mata Kuliah
                </a>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Kode</th>
                            <th class="py-2">Nama Mata Kuliah</th>
                            <th class="py-2">SKS</th>
                            <th class="py-2">Program Studi</th>
                            <th class="py-2 w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($courses as $course)
                            <tr class="border-b">
                                <td class="py-2">{{ $course->code }}</td>
                                <td class="py-2">{{ $course->name }}</td>
                                <td class="py-2">{{ $course->sks }}</td>
                                <td class="py-2">{{ $course->studyProgram->name }}</td>
                                <td class="py-2">
                                    <a href="{{ route('courses.edit', $course) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('courses.destroy', $course) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus mata kuliah ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline ml-2">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-gray-500">Belum ada data mata kuliah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $courses->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>