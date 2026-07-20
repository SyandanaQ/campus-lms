<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Program Studi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('study-programs.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Nama Program Studi</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full border-gray-300 rounded shadow-sm">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Fakultas</label>
                        <select name="faculty_id" class="w-full border-gray-300 rounded shadow-sm">
                            <option value="">-- Pilih Fakultas --</option>
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                    {{ $faculty->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('faculty_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Jenjang</label>
                        <select name="level" class="w-full border-gray-300 rounded shadow-sm">
                            <option value="S1" {{ old('level') == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ old('level') == 'S2' ? 'selected' : '' }}>S2</option>
                            <option value="S3" {{ old('level') == 'S3' ? 'selected' : '' }}>S3</option>
                        </select>
                        @error('level')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Simpan
                    </button>
                    <a href="{{ route('study-programs.index') }}" class="ml-2 text-gray-600">Batal</a>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>