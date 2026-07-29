<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Materi - {{ $class->course->name }} (Kelas {{ $class->class_name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <x-card>
                <a href="{{ route('enrollments.my') }}" class="text-blue-700 hover:underline text-sm font-medium">&larr; Kembali ke KRS Saya</a>
            </x-card>

            @forelse ($materials as $material)
                <x-card>
                    <h3 class="font-medium text-gray-800 mb-3">{{ $material->title }}</h3>

                    @if ($material->type === 'pdf')
                        <x-button href="{{ Storage::url($material->file_path) }}" variant="outline" size="sm" target="_blank">
                            Buka / Download PDF
                        </x-button>
                    @elseif ($material->type === 'youtube')
                        @php
                            preg_match('/(?:youtube\.com\/(?:.*v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $material->url, $matches);
                            $videoId = $matches[1] ?? null;
                        @endphp
                        @if ($videoId)
                            <div class="aspect-video rounded-lg overflow-hidden">
                                <iframe class="w-full h-full"
                                        src="https://www.youtube.com/embed/{{ $videoId }}"
                                        frameborder="0" allowfullscreen></iframe>
                            </div>
                        @else
                            <a href="{{ $material->url }}" target="_blank" class="text-blue-700 hover:underline">{{ $material->url }}</a>
                        @endif
                    @else
                        <x-button href="{{ $material->url }}" variant="outline" size="sm" target="_blank">
                            Buka Link
                        </x-button>
                    @endif
                </x-card>
            @empty
                <x-card>
                    <p class="text-gray-400 text-center py-2">Belum ada materi untuk kelas ini.</p>
                </x-card>
            @endforelse

        </div>
    </div>
</x-app-layout>