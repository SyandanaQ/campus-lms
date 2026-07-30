<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            KRS Saya
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>

                @if (session('success'))
                    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
                @endif

                <div class="mb-4">
                    <a href="{{ route('enrollments.available') }}" class="text-blue-700 hover:underline text-sm font-medium">&larr; Lihat Kelas Tersedia</a>
                </div>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-2 font-medium text-gray-600">Mata Kuliah</th>
                            <th class="py-2 font-medium text-gray-600">Kelas</th>
                            <th class="py-2 font-medium text-gray-600">Dosen</th>
                            <th class="py-2 font-medium text-gray-600">Tahun Ajaran</th>
                            <th class="py-2 font-medium text-gray-600">Status</th>
                            <th class="py-2 font-medium text-gray-600">Menu</th>
                            <th class="py-2 font-medium text-gray-600 w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enrollments as $enrollment)
                            <tr class="border-b border-gray-50">
                                <td class="py-3 text-gray-800">{{ $enrollment->classRoom->course->name }}</td>
                                <td class="py-3 text-gray-800">{{ $enrollment->classRoom->class_name }}</td>
                                <td class="py-3 text-gray-800">{{ $enrollment->classRoom->lecturer->user->name }}</td>
                                <td class="py-3 text-gray-800">{{ $enrollment->classRoom->academicYear->year }}</td>
                                <td class="py-3">
                                    <x-badge color="green">{{ ucfirst($enrollment->status) }}</x-badge>
                                </td>
                                <td class="py-3 text-sm space-x-2">
                                    <a href="{{ route('student-materials.index', $enrollment->classRoom) }}" class="text-blue-700 hover:underline">Materi</a>
                                    <a href="{{ route('student-assignments.index', $enrollment->classRoom) }}" class="text-blue-700 hover:underline">Tugas</a>
                                    <a href="{{ route('student-quizzes.index', $enrollment->classRoom) }}" class="text-blue-700 hover:underline">Kuis</a>
                                    <a href="{{ route('forum.index', $enrollment->classRoom) }}" class="text-blue-700 hover:underline">Forum</a>
                                </td>
                                <td class="py-3">
                                    <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" onsubmit="return confirm('Yakin batalkan KRS ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-button variant="danger" size="sm">Batalkan</x-button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-gray-400 text-center">Anda belum mengambil kelas apapun.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </x-card>
        </div>
    </div>
</x-app-layout>