<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Kelas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-card>

                <form action="{{ route('classes.update', $class) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Mata Kuliah</label>
                        <select name="course_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id', $class->course_id) == $course->id ? 'selected' : '' }}>
                                    {{ $course->code }} - {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Dosen Pengampu</label>
                        <select name="lecturer_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach ($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}" {{ old('lecturer_id', $class->lecturer_id) == $lecturer->id ? 'selected' : '' }}>
                                    {{ $lecturer->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('lecturer_id')
                            <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tahun Ajaran</label>
                        <select name="academic_year_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach ($academicYears as $academicYear)
                                <option value="{{ $academicYear->id }}" {{ old('academic_year_id', $class->academic_year_id) == $academicYear->id ? 'selected' : '' }}>
                                    {{ $academicYear->year }} ({{ ucfirst($academicYear->semester) }}) {{ $academicYear->is_active ? '- Aktif' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year_id')
                            <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-input name="class_name" label="Nama Kelas" :value="$class->class_name" />
                    <x-input name="capacity" label="Kapasitas" type="number" :value="$class->capacity" />

                    <div class="flex items-center gap-3">
                        <x-button>Update</x-button>
                        <a href="{{ route('classes.index') }}" class="text-sm text-gray-600 hover:text-gray-800">Batal</a>
                    </div>
                </form>

            </x-card>
        </div>
    </div>
</x-app-layout>