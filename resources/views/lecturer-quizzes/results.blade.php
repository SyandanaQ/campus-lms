<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Hasil Kuis - {{ $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('quizzes.index', $quiz->classRoom) }}" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke Daftar Kuis</a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">NIM</th>
                            <th class="py-2">Nama</th>
                            <th class="py-2">Skor</th>
                            <th class="py-2">Waktu Submit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attempts as $attempt)
                            <tr class="border-b">
                                <td class="py-2">{{ $attempt->student->nim }}</td>
                                <td class="py-2">{{ $attempt->student->user->name }}</td>
                                <td class="py-2">{{ $attempt->score }}</td>
                                <td class="py-2 text-sm">{{ $attempt->submitted_at->format('d M Y, H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-4 text-gray-500">Belum ada yang mengerjakan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>