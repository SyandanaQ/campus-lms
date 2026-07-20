<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Tahun Ajaran
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('academic-years.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Tahun (contoh: 2025/2026)</label>
                        <input type="text" name="year" value="{{ old('year') }}"
                               class="w-full border-gray-300 rounded shadow-sm">
                        @error('year')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Semester</label>
                        <select name="semester" class="w-full border-gray-300 rounded shadow-sm">
                            <option value="ganjil" {{ old('semester') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="genap" {{ old('semester') == 'genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                        @error('semester')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                                   class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Jadikan tahun ajaran aktif</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">Mengaktifkan ini akan menonaktifkan tahun ajaran lain yang sedang aktif.</p>
                    </div>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Simpan
                    </button>
                    <a href="{{ route('academic-years.index') }}" class="ml-2 text-gray-600">Batal</a>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>