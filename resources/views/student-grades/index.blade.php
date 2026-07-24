<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nilai Saya
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Mata Kuliah</th>
                            <th class="py-2">Tahun Ajaran</th>
                            <th class="py-2">Nilai</th>
                            <th class="py-2">Huruf</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($grades as $grade)
                            <tr class="border-b">
                                <td class="py-2">{{ $grade->classRoom->course->name }}</td>
                                <td class="py-2">{{ $grade->classRoom->academicYear->year }}</td>
                                <td class="py-2">{{ $grade->score }}</td>
                                <td class="py-2 font-medium">{{ $grade->letter }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-4 text-gray-500">Belum ada nilai akhir yang tersedia.</td></tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>