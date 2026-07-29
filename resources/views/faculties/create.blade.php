<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Fakultas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-card>

                <form action="{{ route('faculties.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <x-input name="name" label="Nama Fakultas" />

                    <div class="flex items-center gap-3">
                        <x-button>Simpan</x-button>
                        <a href="{{ route('faculties.index') }}" class="text-sm text-gray-600 hover:text-gray-800">Batal</a>
                    </div>
                </form>

            </x-card>
        </div>
    </div>
</x-app-layout>