<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Mata Kuliah
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('courses.update', $course) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Kode Mata Kuliah</label>
                        <input type="text" name="code" value="{{ old('code', $course->code) }}"
                               class="w-full border-gray-300 rounded shadow-sm">
                        @error('code')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Nama Mata Kuliah</label>
                        <input type="text" name="name" value="{{ old('name', $course->name) }}"
                               class="w-full border-gray-300 rounded shadow-sm">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">SKS</label>
                        <input type="number" name="sks" value="{{ old('sks', $course->sks) }}" min="1" max="6"
                               class="w-full border-gray-300 rounded shadow-sm">
                        @error('sks')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Program Studi</label>
                        <select name="study_program_id" class="w-full border-gray-300 rounded shadow-sm">
                            @foreach ($studyPrograms as $studyProgram)
                                <option value="{{ $studyProgram->id }}" {{ old('study_program_id', $course->study_program_id) == $studyProgram->id ? 'selected' : '' }}>
                                    {{ $studyProgram->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('study_program_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Update
                    </button>
                    <a href="{{ route('courses.index') }}" class="ml-2 text-gray-600">Batal</a>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>