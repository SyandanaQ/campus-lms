<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Program Studi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-card>

                <form action="{{ route('study-programs.update', $studyProgram) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <x-input name="name" label="Nama Program Studi" :value="$studyProgram->name" />

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Fakultas</label>
                        <select name="faculty_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->id }}" {{ old('faculty_id', $studyProgram->faculty_id) == $faculty->id ? 'selected' : '' }}>
                                    {{ $faculty->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('faculty_id')
                            <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenjang</label>
                        <select name="level" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach (['S1', 'S2', 'S3'] as $lvl)
                                <option value="{{ $lvl }}" {{ old('level', $studyProgram->level) == $lvl ? 'selected' : '' }}>
                                    {{ $lvl }}
                                </option>
                            @endforeach
                        </select>
                        @error('level')
                            <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <x-button>Update</x-button>
                        <a href="{{ route('study-programs.index') }}" class="text-sm text-gray-600 hover:text-gray-800">Batal</a>
                    </div>
                </form>

            </x-card>
        </div>
    </div>
</x-app-layout>