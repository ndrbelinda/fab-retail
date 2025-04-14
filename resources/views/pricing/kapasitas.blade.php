<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    {{-- Tombol Navigasi --}}
    <div class="mb-6 flex items-center space-x-2 text-gray-700 text-sm">
        <a href="{{ route('pricing.kapasitas') }}" 
           class="{{ request()->routeIs('pricing.kapasitas') ? 'text-blue-500 font-semibold underline' : 'font-semibold' }}">
            Pricing Kapasitas
        </a>
        <span>|</span>
        <a href="{{ route('pricing.perangkat') }}" 
           class="{{ request()->routeIs('pricing.perangkat') ? 'text-blue-500 font-semibold underline' : 'font-semibold' }}">
            Pricing Perangkat
        </a>
    </div>

    @include('pricing.partials.table-kapasitas', ['kapasitas' => $kapasitas])


</x-layout>