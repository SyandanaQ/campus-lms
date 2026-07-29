<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Tahun Ajaran
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-card>

                <form action="{{ route('academic-years.update', $academicYear) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <x-input name="year" label="Tahun" :value="$academicYear->year" />

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Semester</label>
                        <select name="semester" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="ganjil" {{ old('semester', $academicYear->semester) == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="genap" {{ old('semester', $academicYear->semester) == 'genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                        @error('semester')
                            <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $academicYear->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-700 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Jadikan tahun ajaran aktif</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">Mengaktifkan ini akan menonaktifkan tahun ajaran lain yang sedang aktif.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-button>Update</x-button>
                        <a href="{{ route('academic-years.index') }}" class="text-sm text-gray-600 hover:text-gray-800">Batal</a>
                    </div>
                </form>

            </x-card>
        </div>
    </div>
</x-app-layout>