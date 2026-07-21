<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Kelas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('classes.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Mata Kuliah</label>
                        <select name="course_id" class="w-full border-gray-300 rounded shadow-sm">
                            <option value="">-- Pilih Mata Kuliah --</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->code }} - {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Dosen Pengampu</label>
                        <select name="lecturer_id" class="w-full border-gray-300 rounded shadow-sm">
                            <option value="">-- Pilih Dosen --</option>
                            @foreach ($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}" {{ old('lecturer_id') == $lecturer->id ? 'selected' : '' }}>
                                    {{ $lecturer->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('lecturer_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Tahun Ajaran</label>
                        <select name="academic_year_id" class="w-full border-gray-300 rounded shadow-sm">
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @foreach ($academicYears as $academicYear)
                                <option value="{{ $academicYear->id }}" {{ old('academic_year_id') == $academicYear->id ? 'selected' : '' }}>
                                    {{ $academicYear->year }} ({{ ucfirst($academicYear->semester) }}) {{ $academicYear->is_active ? '- Aktif' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Nama Kelas (contoh: A, B, C)</label>
                        <input type="text" name="class_name" value="{{ old('class_name') }}"
                               class="w-full border-gray-300 rounded shadow-sm">
                        @error('class_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Kapasitas</label>
                        <input type="number" name="capacity" value="{{ old('capacity', 40) }}" min="1" max="200"
                               class="w-full border-gray-300 rounded shadow-sm">
                        @error('capacity')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Simpan
                    </button>
                    <a href="{{ route('classes.index') }}" class="ml-2 text-gray-600">Batal</a>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>