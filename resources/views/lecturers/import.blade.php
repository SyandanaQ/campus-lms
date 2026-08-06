<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Import Data Dosen
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-card>

                <a href="{{ route('lecturers.index') }}" class="text-blue-700 hover:underline text-sm font-medium">&larr; Kembali ke Data Dosen</a>

                <div class="mt-6 space-y-5">
                    <x-alert type="info">
                        Format CSV: <strong>name, email, nidn, study_program</strong>. Kolom <strong>study_program</strong> harus sesuai nama program studi yang sudah terdaftar di sistem.
                    </x-alert>

                    <x-button href="{{ route('lecturers.import.template') }}" variant="secondary" size="sm">
                        Download Template CSV
                    </x-button>

                    <form action="{{ route('lecturers.import.process') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Upload File CSV</label>
                            <input type="file" name="file" accept=".csv" class="w-full border-gray-300 shadow-sm text-sm">
                            @error('file')
                                <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <x-button>Import Data</x-button>
                    </form>
                </div>

            </x-card>
        </div>
    </div>
</x-app-layout>