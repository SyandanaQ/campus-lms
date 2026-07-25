<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            KRS Saya
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

                <div class="mb-4">
                    <a href="{{ route('enrollments.available') }}" class="text-blue-600 hover:underline">&larr; Lihat Kelas Tersedia</a>
                </div>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Mata Kuliah</th>
                            <th class="py-2">Kelas</th>
                            <th class="py-2">Dosen</th>
                            <th class="py-2">Tahun Ajaran</th>
                            <th class="py-2">Status</th>
                            <th class="py-2">Materi</th>
                            <th class="py-2 w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enrollments as $enrollment)
                            <tr class="border-b">
                                <td class="py-2">{{ $enrollment->classRoom->course->name }}</td>
                                <td class="py-2">{{ $enrollment->classRoom->class_name }}</td>
                                <td class="py-2">{{ $enrollment->classRoom->lecturer->user->name }}</td>
                                <td class="py-2">{{ $enrollment->classRoom->academicYear->year }}</td>
                                <td class="py-2 capitalize">{{ $enrollment->status }}</td>
<td class="py-2">
    <a href="{{ route('student-materials.index', $enrollment->classRoom) }}" class="text-blue-600 hover:underline text-sm">Materi</a>
    <a href="{{ route('student-assignments.index', $enrollment->classRoom) }}" class="text-blue-600 hover:underline text-sm ml-2">Tugas</a>
    <a href="{{ route('student-quizzes.index', $enrollment->classRoom) }}" class="text-blue-600 hover:underline text-sm ml-2">Kuis</a>
    <a href="{{ route('forum.index', $enrollment->classRoom) }}" class="text-blue-600 hover:underline text-sm ml-2">Forum</a>
</td>
                            <td class="py-2">
                                    <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" onsubmit="return confirm('Yakin batalkan KRS ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">Batalkan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-gray-500">Anda belum mengambil kelas apapun.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>