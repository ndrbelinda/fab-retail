<!-- resources/views/kapasitas/create.blade.php -->
<x-layout>
    <x-slot:title>
        <div class="flex items-center">
            <!-- Icon Panah Kiri -->
            <a href="{{ route('kapasitas.index') }}" class="text-black hover:text-gray-700 mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <!-- Judul -->
            <span>{{ $title }}</span>
        </div>
    </x-slot:title>

    <!-- Form Input -->
    <form action="{{ route('kapasitas.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="id_produk" class="block text-sm font-medium text-gray-700">Nama Produk</label>
            <select name="id_produk" id="id_produk" class="mt-1 block w-64 rounded-md border-gray-300 shadow-sm h-10 px-3 py-2 focus:border-blue-500 focus:ring-blue-500">
                <!-- Opsi default kosong -->
                <option value="">Pilih Produk</option>
                
                <!-- Opsi dari data produk -->
                @foreach($produk as $item)
                    <option value="{{ $item->id }}">{{ $item->nama_produk }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="besar_kapasitas" class="block text-sm font-medium text-gray-700">Besar Kapasitas</label>
            <input type="text" name="besar_kapasitas" id="besar_kapasitas" class="mt-1 block w-64 rounded-md border-gray-300 shadow-sm h-10 px-3 py-2 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="tarif_kapasitas" class="block text-sm font-medium text-gray-700">Tarif Kapasitas</label>
            <input type="number" name="tarif_kapasitas" id="tarif_kapasitas" class="mt-1 block w-64 rounded-md border-gray-300 shadow-sm h-10 px-3 py-2 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="deskripsi_kapasitas" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="deskripsi_kapasitas" id="deskripsi_kapasitas" rows="4" class="mt-1 block w-64 rounded-md border-gray-300 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tampil di e-Katalog?</label>
            <div class="mt-1">
                <label class="inline-flex items-center">
                    <input type="radio" name="tampil_ekatalog" value="1" class="form-radio text-blue-500 focus:ring-blue-500">
                    <span class="ml-2">Ya</span>
                </label>
                <label class="inline-flex items-center ml-6">
                    <input type="radio" name="tampil_ekatalog" value="0" class="form-radio text-blue-500 focus:ring-blue-500">
                    <span class="ml-2">Tidak</span>
                </label>
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="w-64">
            <button type="submit" name="action" value="ajukan" class="w-full bg-blue-500 text-white px-3 py-1.5 rounded text-sm hover:bg-blue-600">
                Simpan dan ajukan
            </button>
        </div>

        <!-- Tombol Simpan sebagai Draft -->
        <div class="w-64">
            <button type="submit" name="action" value="simpan" class="w-full text-red-500 px-3 py-1.5 rounded text-sm border border-red-500 hover:bg-red-50">
                Simpan sebagai Draft
            </button>
        </div>
    </form>
</x-layout>