<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pengumuman
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @forelse ($announcements as $announcement)
                <x-card>
                    <div class="flex justify-between items-start">
                        <h4 class="font-medium text-gray-800">{{ $announcement->title }}</h4>
                        @if ($announcement->class_id)
                            <x-badge color="blue">{{ $announcement->classRoom->course->name }}</x-badge>
                        @else
                            <x-badge color="gray">Global</x-badge>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400 mt-1">{{ $announcement->created_at->format('d M Y, H:i') }}</p>
                    <p class="text-gray-600 mt-3 whitespace-pre-line">{{ $announcement->body }}</p>
                </x-card>
            @empty
                <x-card>
                    <p class="text-gray-400 text-center py-2">Belum ada pengumuman.</p>
                </x-card>
            @endforelse

        </div>
    </div>
</x-app-layout>