<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pengumuman
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @forelse ($announcements as $announcement)
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-start">
                        <h4 class="font-medium text-gray-800">{{ $announcement->title }}</h4>
                        @if ($announcement->class_id)
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">{{ $announcement->classRoom->course->name }}</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">Global</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400 mt-1">{{ $announcement->created_at->format('d M Y, H:i') }}</p>
                    <p class="text-gray-600 mt-3 whitespace-pre-line">{{ $announcement->body }}</p>
                </div>
            @empty
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-500">
                    Belum ada pengumuman.
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>