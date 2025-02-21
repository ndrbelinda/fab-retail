<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    {{-- Button Tambah --}}
    <div class="mb-8 mt-8">
        <x-button.tambah href="{{ route('kapasitas.create') }}" label="Tambah Kapasitas Internet" />
    </div>

    {{-- Tombol Navigasi --}}
    <div class="mb-6 flex items-center space-x-2 text-gray-700 text-sm">
        <a href="{{ route('kapasitas.index') }}" 
           class="{{ request()->routeIs('kapasitas.index') ? 'text-blue-500 font-semibold underline' : 'font-semibold' }}">
            Daftar
        </a>
        <span>|</span>
        <a href="{{ route('kapasitas.verify') }}" 
           class="{{ request()->routeIs('kapasitas.verify') ? 'text-blue-500 font-semibold underline' : 'font-semibold' }}">
            Menunggu Verifikasi
        </a>
    </div>

    {{-- Panggil Partial View Tabel --}}
    @include('kapasitas.partials.table', ['kapasitas' => $kapasitas])

</x-layout>