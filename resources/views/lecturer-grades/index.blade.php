<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nilai Akhir - {{ $class->course->name }} (Kelas {{ $class->class_name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif

            <x-card>
                <a href="{{ route('lecturer-classes.show', $class) }}" class="text-blue-700 hover:underline text-sm font-medium">&larr; Kembali ke Detail Kelas</a>
                <x-button href="{{ route('final-grades.export', $class) }}" variant="secondary" size="sm" class="float-right">
                    Export ke CSV
                </x-button>
            </x-card>

            <x-card>
                <form action="{{ route('final-grades.store', $class) }}" method="POST">
                    @csrf

                    <table class="w-full text-left border-collapse mb-4">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="py-2 font-medium text-gray-600">NIM</th>
                                <th class="py-2 font-medium text-gray-600">Nama</th>
                                <th class="py-2 font-medium text-gray-600 w-40">Nilai (0-100)</th>
                                <th class="py-2 font-medium text-gray-600 w-24">Huruf</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                @php $grade = $grades->get($student->id); @endphp
                                <tr class="border-b border-gray-50">
                                    <td class="py-3 text-gray-800">{{ $student->nim }}</td>
                                    <td class="py-3 text-gray-800">{{ $student->user->name }}</td>
                                    <td class="py-3">
                                        <input type="number" name="scores[{{ $student->id }}]"
                                               value="{{ old('scores.' . $student->id, $grade->score ?? '') }}"
                                               min="0" max="100" step="0.01"
                                               class="w-28 border-gray-300 rounded-lg shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                    </td>
                                    <td class="py-3">
                                        @if ($grade)
                                            <x-badge color="blue">{{ $grade->letter }}</x-badge>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-6 text-gray-400 text-center">Belum ada mahasiswa terdaftar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if ($students->isNotEmpty())
                        <x-button>Simpan Semua Nilai</x-button>
                    @endif
                </form>
            </x-card>

        </div>
    </div>
</x-app-layout>