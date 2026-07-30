<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelas Tersedia
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>

                @if (session('success'))
                    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
                @endif

                @if (session('error'))
                    <x-alert type="error" class="mb-4">{{ session('error') }}</x-alert>
                @endif

                <div class="mb-4">
                    <a href="{{ route('enrollments.my') }}" class="text-blue-700 hover:underline text-sm font-medium">Lihat KRS Saya &rarr;</a>
                </div>

                @if (!$activeYear)
                    <p class="text-gray-400 text-center py-4">Belum ada tahun ajaran aktif saat ini.</p>
                @else
                    <p class="mb-4 text-sm text-gray-500">Tahun Ajaran: <strong class="text-gray-800">{{ $activeYear->year }} ({{ ucfirst($activeYear->semester) }})</strong></p>

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="py-2 font-medium text-gray-600">Mata Kuliah</th>
                                <th class="py-2 font-medium text-gray-600">Kelas</th>
                                <th class="py-2 font-medium text-gray-600">Dosen</th>
                                <th class="py-2 font-medium text-gray-600">Kuota</th>
                                <th class="py-2 font-medium text-gray-600 w-40">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($classes as $class)
                                <tr class="border-b border-gray-50">
                                    <td class="py-3 text-gray-800">{{ $class->course->name }}</td>
                                    <td class="py-3 text-gray-800">{{ $class->class_name }}</td>
                                    <td class="py-3 text-gray-800">{{ $class->lecturer->user->name }}</td>
                                    <td class="py-3 text-gray-800">{{ $class->enrollments->count() }}/{{ $class->capacity }}</td>
                                    <td class="py-3">
                                        @if (in_array($class->id, $enrolledClassIds))
                                            <x-badge color="gray">Sudah Diambil</x-badge>
                                        @elseif ($class->enrollments->count() >= $class->capacity)
                                            <x-badge color="red">Penuh</x-badge>
                                        @else
                                            <form action="{{ route('enrollments.store', $class) }}" method="POST">
                                                @csrf
                                                <x-button size="sm">Ambil KRS</x-button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-gray-400 text-center">Belum ada kelas dibuka untuk tahun ajaran ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif

            </x-card>
        </div>
    </div>
</x-app-layout>