<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Program Studi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-card>

                <form action="{{ route('study-programs.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <x-input name="name" label="Nama Program Studi" />

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Fakultas</label>
                        <select name="faculty_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Pilih Fakultas --</option>
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
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
                            <option value="S1" {{ old('level') == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ old('level') == 'S2' ? 'selected' : '' }}>S2</option>
                            <option value="S3" {{ old('level') == 'S3' ? 'selected' : '' }}>S3</option>
                        </select>
                        @error('level')
                            <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <x-button>Simpan</x-button>
                        <a href="{{ route('study-programs.index') }}" class="text-sm text-gray-600 hover:text-gray-800">Batal</a>
                    </div>
                </form>

            </x-card>
        </div>
    </div>
</x-app-layout>