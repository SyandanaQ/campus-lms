<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $class->course->name }} - Kelas {{ $class->class_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-card>
                <a href="{{ route('lecturer-classes.index') }}" class="text-blue-700 hover:underline text-sm font-medium">&larr; Kembali ke Kelas Saya</a>

                <div class="flex flex-wrap gap-2 mt-4">
                    <x-button href="{{ route('materials.index', $class) }}" variant="outline" size="sm">Kelola Materi</x-button>
                    <x-button href="{{ route('assignments.index', $class) }}" variant="outline" size="sm">Kelola Tugas</x-button>
                    <x-button href="{{ route('quizzes.index', $class) }}" variant="outline" size="sm">Kelola Kuis</x-button>
                    <x-button href="{{ route('final-grades.index', $class) }}" variant="outline" size="sm">Nilai Akhir</x-button>
                    <x-button href="{{ route('announcements.index', $class) }}" variant="outline" size="sm">Pengumuman</x-button>
                    <x-button href="{{ route('forum.index', $class) }}" variant="outline" size="sm">Forum Diskusi</x-button>
                </div>
            </x-card>

            <x-card>
                <p class="text-sm text-gray-500 mb-4">
                    Tahun Ajaran: <strong class="text-gray-800">{{ $class->academicYear->year }} ({{ ucfirst($class->academicYear->semester) }})</strong>
                    &middot; Kapasitas: <strong class="text-gray-800">{{ $class->enrollments->count() }}/{{ $class->capacity }}</strong>
                </p>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-2 font-medium text-gray-600">NIM</th>
                            <th class="py-2 font-medium text-gray-600">Nama Mahasiswa</th>
                            <th class="py-2 font-medium text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($class->enrollments as $enrollment)
                            <tr class="border-b border-gray-50">
                                <td class="py-3 text-gray-800">{{ $enrollment->student->nim }}</td>
                                <td class="py-3 text-gray-800">{{ $enrollment->student->user->name }}</td>
                                <td class="py-3 text-gray-800 capitalize">{{ $enrollment->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-gray-400 text-center">Belum ada mahasiswa terdaftar di kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>

        </div>
    </div>
</x-app-layout>