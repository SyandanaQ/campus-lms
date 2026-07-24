<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Materi - {{ $class->course->name }} (Kelas {{ $class->class_name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('enrollments.my') }}" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke KRS Saya</a>
            </div>

            @forelse ($materials as $material)
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-medium text-gray-800 mb-3">{{ $material->title }}</h3>

                    @if ($material->type === 'pdf')
                        <a href="{{ Storage::url($material->file_path) }}" target="_blank"
                           class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                            Buka / Download PDF
                        </a>
                    @elseif ($material->type === 'youtube')
                        @php
                            preg_match('/(?:youtube\.com\/(?:.*v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $material->url, $matches);
                            $videoId = $matches[1] ?? null;
                        @endphp
                        @if ($videoId)
                            <div class="aspect-video">
                                <iframe class="w-full h-full rounded"
                                        src="https://www.youtube.com/embed/{{ $videoId }}"
                                        frameborder="0" allowfullscreen></iframe>
                            </div>
                        @else
                            <a href="{{ $material->url }}" target="_blank" class="text-blue-600 hover:underline">{{ $material->url }}</a>
                        @endif
                    @else
                        <a href="{{ $material->url }}" target="_blank"
                           class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                            Buka Link
                        </a>
                    @endif
                </div>
            @empty
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-500">
                    Belum ada materi untuk kelas ini.
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>