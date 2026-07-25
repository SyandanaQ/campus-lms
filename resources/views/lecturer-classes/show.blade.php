<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $class->course->name }} - Kelas {{ $class->class_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

<div class="mb-4">
    <a href="{{ route('lecturer-classes.index') }}" class="text-blue-600 hover:underline">&larr; Kembali ke Kelas Saya</a>
    <a href="{{ route('materials.index', $class) }}" class="ml-4 text-blue-600 hover:underline">Kelola Materi</a>
    <a href="{{ route('assignments.index', $class) }}" class="ml-4 text-blue-600 hover:underline">Kelola Tugas</a>
    <a href="{{ route('quizzes.index', $class) }}" class="ml-4 text-blue-600 hover:underline">Kelola Kuis</a>
    <a href="{{ route('final-grades.index', $class) }}" class="ml-4 text-blue-600 hover:underline">Nilai Akhir</a>
    <a href="{{ route('announcements.index', $class) }}" class="ml-4 text-blue-600 hover:underline">Pengumuman &rarr;</a>
</div>
<p class="mb-4 text-sm text-gray-500">
                    Tahun Ajaran: <strong>{{ $class->academicYear->year }} ({{ ucfirst($class->academicYear->semester) }})</strong>
                    &middot; Kapasitas: <strong>{{ $class->enrollments->count() }}/{{ $class->capacity }}</strong>
                </p>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">NIM</th>
                            <th class="py-2">Nama Mahasiswa</th>
                            <th class="py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($class->enrollments as $enrollment)
                            <tr class="border-b">
                                <td class="py-2">{{ $enrollment->student->nim }}</td>
                                <td class="py-2">{{ $enrollment->student->user->name }}</td>
                                <td class="py-2 capitalize">{{ $enrollment->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-gray-500">Belum ada mahasiswa terdaftar di kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>