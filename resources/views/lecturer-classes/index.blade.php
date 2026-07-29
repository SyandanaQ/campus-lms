<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelas Saya
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-2 font-medium text-gray-600">Mata Kuliah</th>
                            <th class="py-2 font-medium text-gray-600">Kelas</th>
                            <th class="py-2 font-medium text-gray-600">Tahun Ajaran</th>
                            <th class="py-2 font-medium text-gray-600">Jumlah Mahasiswa</th>
                            <th class="py-2 font-medium text-gray-600 w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classes as $class)
                            <tr class="border-b border-gray-50">
                                <td class="py-3 text-gray-800">{{ $class->course->name }}</td>
                                <td class="py-3 text-gray-800">{{ $class->class_name }}</td>
                                <td class="py-3 text-gray-800">{{ $class->academicYear->year }} ({{ ucfirst($class->academicYear->semester) }})</td>
                                <td class="py-3 text-gray-800">{{ $class->enrollments->count() }}/{{ $class->capacity }}</td>
                                <td class="py-3">
                                    <x-button href="{{ route('lecturer-classes.show', $class) }}" variant="primary" size="sm">
                                        Lihat Detail
                                    </x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-gray-400 text-center">Anda belum mengampu kelas apapun.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </x-card>
        </div>
    </div>
</x-app-layout>