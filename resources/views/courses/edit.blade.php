<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Mata Kuliah
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-card>

                <form action="{{ route('courses.update', $course) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <x-input name="code" label="Kode Mata Kuliah" :value="$course->code" />
                    <x-input name="name" label="Nama Mata Kuliah" :value="$course->name" />
                    <x-input name="sks" label="SKS" type="number" :value="$course->sks" />

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Program Studi</label>
                        <select name="study_program_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach ($studyPrograms as $studyProgram)
                                <option value="{{ $studyProgram->id }}" {{ old('study_program_id', $course->study_program_id) == $studyProgram->id ? 'selected' : '' }}>
                                    {{ $studyProgram->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('study_program_id')
                            <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <x-button>Update</x-button>
                        <a href="{{ route('courses.index') }}" class="text-sm text-gray-600 hover:text-gray-800">Batal</a>
                    </div>
                </form>

            </x-card>
        </div>
    </div>
</x-app-layout>