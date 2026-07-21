<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Kelas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <a href="{{ route('classes.create') }}" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Tambah Kelas
                </a>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Mata Kuliah</th>
                            <th class="py-2">Kelas</th>
                            <th class="py-2">Dosen</th>
                            <th class="py-2">Tahun Ajaran</th>
                            <th class="py-2">Kapasitas</th>
                            <th class="py-2 w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classes as $class)
                            <tr class="border-b">
                                <td class="py-2">{{ $class->course->name }}</td>
                                <td class="py-2">{{ $class->class_name }}</td>
                                <td class="py-2">{{ $class->lecturer->user->name }}</td>
                                <td class="py-2">{{ $class->academicYear->year }} ({{ ucfirst($class->academicYear->semester) }})</td>
                                <td class="py-2">{{ $class->capacity }}</td>
                                <td class="py-2">
                                    <a href="{{ route('classes.edit', $class) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('classes.destroy', $class) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus kelas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline ml-2">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-gray-500">Belum ada data kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $classes->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>