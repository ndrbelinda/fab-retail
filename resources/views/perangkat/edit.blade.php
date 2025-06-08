{{-- resources/views/perangkat/edit.blade.php --}}
<x-layout>
    <x-slot:title>
        <div class="flex items-center">
            <a href="{{ route('perangkat.index') }}" class="text-black hover:text-gray-700 mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <span>Ubah Perangkat</span>
        </div>
    </x-slot:title>

    <form action="{{ route('perangkat.update', $perangkat->id) }}" method="POST" class="space-y-4" enctype="multipart/form-data" onsubmit="return validateForm()">
        @csrf
        @method('PUT')

        <!-- Input: Nama Produk -->
        <div>
            <label for="id_produk" class="block text-sm font-medium text-gray-700">
                Nama Produk <span class="text-red-500">*</span>
            </label>
            <select name="id_produk" id="id_produk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                <option value="">Pilih Produk</option>
                @foreach($produk as $item)
                    <option value="{{ $item->id }}" {{ old('id_produk', $perangkat->id_produk) == $item->id ? 'selected' : '' }}>{{ $item->nama_produk }}</option>
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
            <input 
                type="text" 
                name="jenis_perangkat" 
                id="jenis_perangkat" 
                value="{{ old('jenis_perangkat', $perangkat->jenis_perangkat) }}" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" 
                required
                maxlength="255"
            >
            @error('jenis_perangkat')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input: Gambar Perangkat -->
        <div>
            <label for="gambar_perangkat" class="block text-sm font-medium text-gray-700">
                Gambar Perangkat
            </label>
            <input 
                type="file" 
                name="gambar_perangkat" 
                id="gambar_perangkat" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" 
                accept="image/*"
                onchange="previewImage(this)"
            >
            @error('gambar_perangkat')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
            <div class="mt-2" id="image-preview-container">
                @if($perangkat->gambar_perangkat)
                    <img src="{{ asset($perangkat->gambar_perangkat) }}" alt="Gambar Perangkat" class="w-32 h-32 object-cover rounded">
                @endif
            </div>
        </div>

        <!-- Input: Tarif Perangkat -->
        <div>
            <label for="tarif_perangkat" class="block text-sm font-medium text-gray-700">
                Tarif Perangkat (Rp) <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                name="tarif_perangkat" 
                id="tarif_perangkat" 
                value="{{ old('tarif_perangkat', $perangkat->tarif_perangkat) }}" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" 
                required
                maxlength="9"
                onkeypress="return isNumber(event)"
                oninput="validateNumberInput(this)"
                placeholder="Masukkan angka saja"
            >
            @error('tarif_perangkat')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input: Deskripsi Perangkat -->
        <div>
            <label for="deskripsi_perangkat" class="block text-sm font-medium text-gray-700">
                Deskripsi
            </label>
            <textarea 
                name="deskripsi_perangkat" 
                id="deskripsi_perangkat" 
                rows="4" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border"
                maxlength="500"
                oninput="updateCharCounter(this)"
            >{{ old('deskripsi_perangkat', $perangkat->deskripsi_perangkat) }}</textarea>
            <div class="text-xs text-gray-500 mt-1" id="char-counter">
                {{ strlen(old('deskripsi_perangkat', $perangkat->deskripsi_perangkat)) }}/500 karakter
            </div>
        </div>

        <!-- Input: Tampil di E-Katalog -->
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Tampil di e-Katalog? <span class="text-red-500">*</span>
            </label>
            <div class="mt-1">
                <label class="inline-flex items-center">
                    <input type="radio" name="tampil_ekatalog" value="1" class="form-radio" {{ old('tampil_ekatalog', $perangkat->tampil_ekatalog) == '1' ? 'checked' : '' }} required>
                    <span class="ml-2">Ya</span>
                </label>
                <label class="inline-flex items-center ml-6">
                    <input type="radio" name="tampil_ekatalog" value="0" class="form-radio" {{ old('tampil_ekatalog', $perangkat->tampil_ekatalog) == '0' ? 'checked' : '' }} required>
                    <span class="ml-2">Tidak</span>
                </label>
            </div>
            @error('tampil_ekatalog')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol Aksi -->
        <div class="space-y-2">
            <button type="submit" name="action" value="ajukan" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">
                Simpan dan ajukan
            </button>
            <button type="submit" name="action" value="simpan" class="w-full border border-red-500 text-red-500 p-2 rounded-md hover:bg-red-50">
                Simpan sebagai Draft
            </button>
        </div>
    </form>

    <!-- Duplicate Warning Modal -->
    <div id="duplicateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
            <div class="flex justify-between items-start">
                <h3 class="text-lg font-medium text-gray-900">Peringatan</h3>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-600" id="modalMessage">Jenis perangkat sudah ada untuk produk ini.</p>
            </div>
            <div class="mt-5 flex justify-end">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                    Oke
                </button>
            </div>
        </div>
    </div>

    <script>
        // Inisialisasi counter karakter saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            updateCharCounter(document.getElementById('deskripsi_perangkat'));
        });

        // Fungsi untuk update counter karakter
        function updateCharCounter(textarea) {
            const counter = document.getElementById('char-counter');
            counter.textContent = `${textarea.value.length}/500 karakter`;
        }

        // Number validation functions
        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            const charCode = (evt.which) ? evt.which : evt.keyCode;
            return !(charCode > 31 && (charCode < 48 || charCode > 57));
        }

        function validateNumberInput(input) {
            // Remove non-numeric characters
            input.value = input.value.replace(/[^0-9]/g, '');
            
            // Enforce max length
            if (input.value.length > 9) {
                input.value = input.value.slice(0, 9);
            }
        }

        // Image preview function
        function previewImage(input) {
            const container = document.getElementById('image-preview-container');
            container.innerHTML = '';
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-32 h-32 object-cover rounded mt-2';
                    container.appendChild(img);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Duplicate check functions
        function showModal(message) {
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('duplicateModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('duplicateModal').classList.add('hidden');
        }

        // Show modal if there's error from server
        @if($errors->has('jenis_perangkat'))
            showModal("{{ $errors->first('jenis_perangkat') }}");
        @endif

        // Prevent paste non-numeric characters
        document.getElementById('tarif_perangkat').addEventListener('paste', function(e) {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text/plain').replace(/[^0-9]/g, '');
            document.execCommand('insertText', false, pasteData);
        });

        // Final validation before submit
        function validateForm() {
            const tarifPerangkat = document.getElementById('tarif_perangkat').value;
            
            if (!/^\d+$/.test(tarifPerangkat)) {
                showModal('Tarif Perangkat harus berupa angka');
                return false;
            }
            
            return true;
        }
    </script>
</x-layout>