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
        @if(auth()->check() && auth()->user()->role === 'avp')
            <span>|</span>
            <a href="{{ route('kapasitas.verify') }}" 
            class="{{ request()->routeIs('kapasitas.verify') ? 'text-blue-500 font-semibold underline' : 'font-semibold' }}">
                Menunggu Verifikasi
            </a>
        @endif
    </div>

    {{-- Panggil Partial View Filter --}}
    @include('kapasitas.partials.filter', ['produk' => $produk])

    {{-- Panggil Partial View Tabel --}}
    @include('kapasitas.partials.table', ['kapasitas' => $kapasitas])

</x-layout>