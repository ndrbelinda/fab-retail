{{-- resources/views/perangkat/edit.blade.php --}}
<x-layout>
    <x-slot:title>
        <div class="flex items-center">
            <!-- Icon Panah Kiri -->
            <a href="{{ route('perangkat.index') }}" class="text-black hover:text-gray-700 mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <!-- Judul -->
            <span>Ubah Perangkat</span>
        </div>
    </x-slot:title>

    <!-- Form Input -->
    <form action="{{ route('perangkat.update', $perangkat->id) }}" method="POST" class="space-y-4" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Input: Nama Produk -->
        <div>
            <label for="id_produk" class="block text-sm font-medium text-gray-700">
                Nama Produk <span class="text-red-500">*</span>
            </label>
            <select name="id_produk" id="id_produk" class="mt-1 block w-64 rounded-md border-gray-300 shadow-sm h-10 px-3 py-2 focus:border-blue-500 focus:ring-blue-500 @error('id_produk') border-red-500 @enderror" required>
                <option value="">Pilih Produk</option>
                @foreach($produk as $item)
                    <option value="{{ $item->id }}" {{ $perangkat->id_produk == $item->id ? 'selected' : '' }}>{{ $item->nama_produk }}</option>
                @endforeach
            </select>
            @error('id_produk')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input: Jenis Perangkat -->
        <div>
            <label for="jenis_perangkat" class="block text-sm font-medium text-gray-700">
                Jenis Perangkat <span class="text-red-500">*</span>
            </label>
            <input type="text" name="jenis_perangkat" id="jenis_perangkat" value="{{ old('jenis_perangkat', $perangkat->jenis_perangkat) }}" class="mt-1 block w-64 rounded-md border-gray-300 shadow-sm h-10 px-3 py-2 focus:border-blue-500 focus:ring-blue-500 @error('jenis_perangkat') border-red-500 @enderror" required>
            @error('jenis_perangkat')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input: Gambar Perangkat -->
        <div>
            <label for="gambar_perangkat" class="block text-sm font-medium text-gray-700">
                Gambar Perangkat <span class="text-red-500">*</span>
            </label>
            <input type="file" name="gambar_perangkat" id="gambar_perangkat" class="mt-1 block w-64 rounded-md border-gray-300 shadow-sm h-10 px-3 py-2 focus:border-blue-500 focus:ring-blue-500 @error('gambar_perangkat') border-red-500 @enderror" accept="image/*">
            @error('gambar_perangkat')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
            @if($perangkat->gambar_perangkat)
                <div class="mt-2">
                    <img src="{{ asset($perangkat->gambar_perangkat) }}" alt="Gambar Perangkat" class="w-32 h-32 object-cover rounded">
                </div>
            @endif
        </div>

        <!-- Input: Tarif Perangkat -->
        <div>
            <label for="tarif_perangkat" class="block text-sm font-medium text-gray-700">
                Tarif Perangkat <span class="text-red-500">*</span>
            </label>
            <input type="number" name="tarif_perangkat" id="tarif_perangkat" value="{{ old('tarif_perangkat', $perangkat->tarif_perangkat) }}" class="mt-1 block w-64 rounded-md border-gray-300 shadow-sm h-10 px-3 py-2 focus:border-blue-500 focus:ring-blue-500 @error('tarif_perangkat') border-red-500 @enderror" required
                max="999999999" 
                oninput="limitInputLength(this, 9)">
            @error('tarif_perangkat')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
            <div id="error-message-tarif" class="text-sm text-red-600 mt-1 hidden">
                Maksimal 9 digit angka yang diperbolehkan.
            </div>
        </div>

        <!-- Input: Deskripsi Perangkat (Tidak Required) -->
        <div>
            <label for="deskripsi_perangkat" class="block text-sm font-medium text-gray-700">
                Deskripsi
            </label>
            <textarea name="deskripsi_perangkat" id="deskripsi_perangkat" rows="4" class="mt-1 block w-64 rounded-md border-gray-300 shadow-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500">{{ old('deskripsi_perangkat', $perangkat->deskripsi_perangkat) }}</textarea>
            @error('deskripsi_perangkat')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input: Tampil di E-Katalog -->
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Tampil di e-Katalog? <span class="text-red-500">*</span>
            </label>
            <div class="mt-1">
                <label class="inline-flex items-center">
                    <input type="radio" name="tampil_ekatalog" value="1" class="form-radio text-blue-500 focus:ring-blue-500" {{ old('tampil_ekatalog', $perangkat->tampil_ekatalog) == '1' ? 'checked' : '' }} required>
                    <span class="ml-2">Ya</span>
                </label>
                <label class="inline-flex items-center ml-6">
                    <input type="radio" name="tampil_ekatalog" value="0" class="form-radio text-blue-500 focus:ring-blue-500" {{ old('tampil_ekatalog', $perangkat->tampil_ekatalog) == '0' ? 'checked' : '' }} required>
                    <span class="ml-2">Tidak</span>
                </label>
            </div>
            @error('tampil_ekatalog')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol Simpan dan Ajukan -->
        <div class="w-64">
            <button type="submit" name="action" value="ajukan" class="w-full bg-blue-500 text-white px-3 py-1.5 rounded text-sm hover:bg-blue-600">
                Simpan dan Ajukan
            </button>
        </div>

        <!-- Tombol Simpan sebagai Draft -->
        <div class="w-64">
            <button type="submit" name="action" value="simpan" class="w-full text-red-500 px-3 py-1.5 rounded text-sm border border-red-500 hover:bg-red-50">
                Simpan sebagai Draft
            </button>
        </div>
    </form>

    <!-- JavaScript untuk Membatasi Input -->
    <script>
        function limitInputLength(input, maxLength) {
            const errorMessage = document.getElementById('error-message-tarif');

            if (input.value.length > maxLength) {
                input.value = input.value.slice(0, maxLength); // Potong nilai jika melebihi batas
                errorMessage.classList.remove('hidden'); // Tampilkan pesan error
            } else {
                errorMessage.classList.add('hidden'); // Sembunyikan pesan error
            }
        }
    </script>

</x-layout>