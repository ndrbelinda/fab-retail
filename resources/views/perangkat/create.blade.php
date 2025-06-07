<!-- resources/views/perangkat/create.blade.php -->
<x-layout>
    <x-slot:title>
        <div class="flex items-center">
            <a href="{{ route('perangkat.index') }}" class="text-black hover:text-gray-700 mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <span>{{ $title }}</span>
        </div>
    </x-slot:title>

    <form action="{{ route('perangkat.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data" onsubmit="return validateForm()">
        @csrf

        <!-- Input: Nama Produk -->
        <div>
            <label for="id_produk" class="block text-sm font-medium text-gray-700">
                Nama Produk <span class="text-red-500">*</span>
            </label>
            <select name="id_produk" id="id_produk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                <option value="">Pilih Produk</option>
                @foreach($produk as $item)
                    <option value="{{ $item->id }}" {{ old('id_produk') == $item->id ? 'selected' : '' }}>{{ $item->nama_produk }}</option>
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
            <input type="text" name="jenis_perangkat" id="jenis_perangkat" value="{{ old('jenis_perangkat') }}" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
            @error('jenis_perangkat')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input: Gambar Perangkat -->
        <div>
            <label for="gambar_perangkat" class="block text-sm font-medium text-gray-700">
                Gambar Perangkat <span class="text-red-500">*</span>
            </label>
            <input type="file" name="gambar_perangkat" id="gambar_perangkat" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" 
                   accept="image/*" required>
            @error('gambar_perangkat')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Format: JPEG, PNG, JPG, GIF (Maks. 2MB)</p>
        </div>

        <!-- Input: Tarif Perangkat -->
        <div>
            <label for="tarif_perangkat" class="block text-sm font-medium text-gray-700">
                Tarif Perangkat (Rp) <span class="text-red-500">*</span>
            </label>
            <input type="text" name="tarif_perangkat" id="tarif_perangkat" 
                   value="{{ old('tarif_perangkat') }}" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" 
                   required
                   maxlength="9"
                   onkeypress="return hanyaAngka(event)"
                   oninput="formatAngka(this)"
                   placeholder="Masukkan angka saja">
            @error('tarif_perangkat')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input: Deskripsi Perangkat -->
        <div>
            <label for="deskripsi_perangkat" class="block text-sm font-medium text-gray-700">
                Deskripsi
            </label>
            <textarea name="deskripsi_perangkat" id="deskripsi_perangkat" rows="4" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">{{ old('deskripsi_perangkat') }}</textarea>
        </div>

        <!-- Input: Tampil di E-Katalog -->
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Tampil di e-Katalog? <span class="text-red-500">*</span>
            </label>
            <div class="mt-1">
                <label class="inline-flex items-center mr-4">
                    <input type="radio" name="tampil_ekatalog" value="1" 
                           class="form-radio" {{ old('tampil_ekatalog') == '1' ? 'checked' : '' }} required>
                    <span class="ml-2">Ya</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="tampil_ekatalog" value="0" 
                           class="form-radio" {{ old('tampil_ekatalog') == '0' ? 'checked' : '' }} required>
                    <span class="ml-2">Tidak</span>
                </label>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="space-y-2">
            <button type="submit" name="action" value="ajukan" 
                    class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">
                Simpan dan ajukan
            </button>
            <button type="submit" name="action" value="simpan" 
                    class="w-full border border-red-500 text-red-500 p-2 rounded-md hover:bg-red-50">
                Simpan sebagai Draft
            </button>
        </div>
    </form>

    <!-- Modal untuk Duplikasi Perangkat -->
    <div id="duplicateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg max-w-sm w-full">
            <h3 class="text-lg font-bold mb-4">Peringatan</h3>
            <p id="modalMessage" class="mb-4">Perangkat sudah ada untuk produk ini</p>
            <button onclick="closeModal()" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
                Oke
            </button>
        </div>
    </div>

    <script>
        // Fungsi untuk hanya menerima input angka
        function hanyaAngka(event) {
            const charCode = (event.which) ? event.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        // Fungsi untuk memformat input angka
        function formatAngka(input) {
            // Hapus semua karakter non-digit
            let value = input.value.replace(/\D/g, '');
            
            // Update nilai input
            input.value = value;
            
            // Validasi panjang maksimal
            if (value.length > 9) {
                input.value = value.slice(0, 9);
            }
        }

        // Fungsi untuk handle paste event (mencegah paste karakter non-angka)
        document.getElementById('tarif_perangkat').addEventListener('paste', function(e) {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text/plain').replace(/\D/g, '');
            document.execCommand('insertText', false, pasteData);
        });

        // Fungsi untuk menampilkan modal
        function showModal(message) {
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('duplicateModal').classList.remove('hidden');
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            document.getElementById('duplicateModal').classList.add('hidden');
        }

        // Tampilkan modal jika ada error dari server
        @if($errors->has('jenis_perangkat'))
            document.addEventListener('DOMContentLoaded', function() {
                showModal("{{ $errors->first('jenis_perangkat') }}");
            });
        @endif

        // Validasi form sebelum submit
        function validateForm() {
            const tarifPerangkat = document.getElementById('tarif_perangkat').value;
            
            if (!/^\d+$/.test(tarifPerangkat)) {
                showModal('Tarif Perangkat harus berupa angka');
                return false;
            }
            
            return true;
        }

        // Optional: Real-time validation via AJAX
        document.getElementById('jenis_perangkat').addEventListener('blur', function() {
            const produkId = document.getElementById('id_produk').value;
            const jenisPerangkat = this.value;
            
            if (!produkId || !jenisPerangkat) return;
            
            fetch('/check-perangkat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id_produk: produkId,
                    jenis_perangkat: jenisPerangkat
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    showModal('Perangkat ini sudah ada untuk produk yang dipilih');
                }
            });
        });
    </script>
</x-layout>