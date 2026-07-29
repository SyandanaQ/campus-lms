<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Hasil Kuis - {{ $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <x-card>
                <a href="{{ route('quizzes.index', $quiz->classRoom) }}" class="text-blue-700 hover:underline text-sm font-medium">&larr; Kembali ke Daftar Kuis</a>
            </x-card>

            <x-card>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-2 font-medium text-gray-600">NIM</th>
                            <th class="py-2 font-medium text-gray-600">Nama</th>
                            <th class="py-2 font-medium text-gray-600">Skor</th>
                            <th class="py-2 font-medium text-gray-600">Waktu Submit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attempts as $attempt)
                            <tr class="border-b border-gray-50">
                                <td class="py-3 text-gray-800">{{ $attempt->student->nim }}</td>
                                <td class="py-3 text-gray-800">{{ $attempt->student->user->name }}</td>
                                <td class="py-3 text-gray-800">{{ $attempt->score }}</td>
                                <td class="py-3 text-sm text-gray-600">{{ $attempt->submitted_at->format('d M Y, H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-6 text-gray-400 text-center">Belum ada yang mengerjakan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>

        </div>
    </div>
</x-app-layout>