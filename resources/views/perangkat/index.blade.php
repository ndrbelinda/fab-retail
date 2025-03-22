<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    {{-- Button Tambah --}}
    <div class="mb-8 mt-8">
        <x-button.tambah href="{{ route('perangkat.create') }}" label="Tambah Perangkat" />
    </div>

    {{-- Tombol Navigasi --}}
    <div class="mb-6 flex items-center space-x-2 text-gray-700 text-sm">
        <a href="{{ route('perangkat.index') }}" 
           class="{{ request()->routeIs('perangkat.index') ? 'text-blue-500 font-semibold underline' : 'font-semibold' }}">
            Daftar
        </a>

        @if(auth()->check() && auth()->user()->role === 'avp')
        <span>|</span>
        <a href="{{ route('perangkat.verify') }}" 
           class="{{ request()->routeIs('perangkat.verify') ? 'text-blue-500 font-semibold underline' : 'font-semibold' }}">
            Menunggu Verifikasi
        </a>
        @endif
        
    </div>

    {{-- Panggil Partial View Filter --}}
    @include('perangkat.partials.filter', ['produk' => $produk])

    {{-- Panggil Partial View Tabel --}}
    @include('perangkat.partials.table', ['perangkat' => $perangkat])

</x-layout>