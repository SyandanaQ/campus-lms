<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelas Tersedia
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

                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-4">
                    <a href="{{ route('enrollments.my') }}" class="text-blue-600 hover:underline">Lihat KRS Saya &rarr;</a>
                </div>

                @if (!$activeYear)
                    <p class="text-gray-500">Belum ada tahun ajaran aktif saat ini.</p>
                @else
                    <p class="mb-4 text-sm text-gray-500">Tahun Ajaran: <strong>{{ $activeYear->year }} ({{ ucfirst($activeYear->semester) }})</strong></p>

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2">Mata Kuliah</th>
                                <th class="py-2">Kelas</th>
                                <th class="py-2">Dosen</th>
                                <th class="py-2">Kuota</th>
                                <th class="py-2 w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($classes as $class)
                                <tr class="border-b">
                                    <td class="py-2">{{ $class->course->name }}</td>
                                    <td class="py-2">{{ $class->class_name }}</td>
                                    <td class="py-2">{{ $class->lecturer->user->name }}</td>
                                    <td class="py-2">{{ $class->enrollments->count() }}/{{ $class->capacity }}</td>
                                    <td class="py-2">
                                        @if (in_array($class->id, $enrolledClassIds))
                                            <span class="text-gray-400">Sudah Diambil</span>
                                        @elseif ($class->enrollments->count() >= $class->capacity)
                                            <span class="text-red-500">Penuh</span>
                                        @else
                                            <form action="{{ route('enrollments.store', $class) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                                    Ambil KRS
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-gray-500">Belum ada kelas dibuka untuk tahun ajaran ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>