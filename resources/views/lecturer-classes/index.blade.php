<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelas Saya
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Mata Kuliah</th>
                            <th class="py-2">Kelas</th>
                            <th class="py-2">Tahun Ajaran</th>
                            <th class="py-2">Jumlah Mahasiswa</th>
                            <th class="py-2 w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classes as $class)
                            <tr class="border-b">
                                <td class="py-2">{{ $class->course->name }}</td>
                                <td class="py-2">{{ $class->class_name }}</td>
                                <td class="py-2">{{ $class->academicYear->year }} ({{ ucfirst($class->academicYear->semester) }})</td>
                                <td class="py-2">{{ $class->enrollments->count() }}/{{ $class->capacity }}</td>
                                <td class="py-2">
                                    <a href="{{ route('lecturer-classes.show', $class) }}" class="text-blue-600 hover:underline">Lihat Mahasiswa</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-gray-500">Anda belum mengampu kelas apapun.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>