{{-- resources/views/faq/index.blade.php --}}
<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    {{-- Button Tambah --}}
    <div class="mb-8 mt-8">
        <x-button.tambah href="{{ route('faq.create') }}" label="Tambah FAQ" />
    </div>

    {{-- Tombol Navigasi --}}
    <div class="mb-6 flex items-center space-x-2 text-gray-700 text-sm">
        <a href="{{ route('faq.index') }}" 
           class="{{ request()->routeIs('faq.index') ? 'text-blue-500 font-semibold underline' : 'font-semibold' }}">
            Daftar
        </a>

        @if(auth()->check() && auth()->user()->role === 'avp')
        <span>|</span>
        <a href="{{ route('faq.verify') }}" 
           class="{{ request()->routeIs('faq.verify') ? 'text-blue-500 font-semibold underline' : 'font-semibold' }}">
            Menunggu Verifikasi
        </a>
        @endif
        
    </div>

     {{-- Panggil Partial View Filter --}}
    @include('faq.partials.filter', ['produk' => $produk])

    {{-- Panggil Partial View Tabel --}}
    @include('faq.partials.table', ['faq' => $faq])

</x-layout>