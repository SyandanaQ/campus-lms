<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Mahasiswa
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-card>

                <x-alert type="info" class="mb-5">
                    Akun login akan dibuat otomatis dengan password default: <strong>password123</strong>
                </x-alert>

                <form action="{{ route('students.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <x-input name="name" label="Nama Lengkap" />
                    <x-input name="email" label="Email" type="email" />
                    <x-input name="nim" label="NIM" />

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Program Studi</label>
                        <select name="study_program_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach ($studyPrograms as $studyProgram)
                                <option value="{{ $studyProgram->id }}" {{ old('study_program_id') == $studyProgram->id ? 'selected' : '' }}>
                                    {{ $studyProgram->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('study_program_id')
                            <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-input name="angkatan" label="Angkatan" type="number" />

                    <div class="flex items-center gap-3">
                        <x-button>Simpan</x-button>
                        <a href="{{ route('students.index') }}" class="text-sm text-gray-600 hover:text-gray-800">Batal</a>
                    </div>
                </form>

            </x-card>
        </div>
    </div>
</x-app-layout>