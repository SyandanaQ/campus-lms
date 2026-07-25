<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nilai Akhir - {{ $class->course->name }} (Kelas {{ $class->class_name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('lecturer-classes.show', $class) }}" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke Detail Kelas</a>
                <a href="{{ route('final-grades.export', $class) }}" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                    Export ke CSV
                </a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('final-grades.store', $class) }}" method="POST">
                    @csrf

                    <table class="w-full text-left border-collapse mb-4">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2">NIM</th>
                                <th class="py-2">Nama</th>
                                <th class="py-2 w-32">Nilai (0-100)</th>
                                <th class="py-2 w-20">Huruf</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                @php $grade = $grades->get($student->id); @endphp
                                <tr class="border-b">
                                    <td class="py-2">{{ $student->nim }}</td>
                                    <td class="py-2">{{ $student->user->name }}</td>
                                    <td class="py-2">
                                        <input type="number" name="scores[{{ $student->id }}]"
                                               value="{{ old('scores.' . $student->id, $grade->score ?? '') }}"
                                               min="0" max="100" step="0.01"
                                               class="w-24 border-gray-300 rounded shadow-sm text-sm">
                                    </td>
                                    <td class="py-2 font-medium">
                                        {{ $grade->letter ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-4 text-gray-500">Belum ada mahasiswa terdaftar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if ($students->isNotEmpty())
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Simpan Semua Nilai
                        </button>
                    @endif
                </form>
            </div>

        </div>
    </div>
</x-app-layout>