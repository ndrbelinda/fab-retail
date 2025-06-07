<!-- resources/views/kapasitas/create.blade.php -->
<x-layout>
    <x-slot:title>
        <div class="flex items-center">
            <a href="{{ route('kapasitas.index') }}" class="text-black hover:text-gray-700 mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <span>{{ $title }}</span>
        </div>
    </x-slot:title>

    <form action="{{ route('kapasitas.store') }}" method="POST" class="space-y-4" onsubmit="return validateForm()">
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

        <!-- Input: Besar Kapasitas -->
        <div>
            <label for="besar_kapasitas" class="block text-sm font-medium text-gray-700">
                Besar Kapasitas (GB) <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                name="besar_kapasitas" 
                id="besar_kapasitas" 
                value="{{ old('besar_kapasitas') }}" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" 
                required
                maxlength="9"
                onkeypress="return isNumber(event)"
                oninput="validateNumberInput(this)"
                placeholder="Masukkan angka saja"
            >
            @error('besar_kapasitas')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input: Tarif Kapasitas -->
        <div>
            <label for="tarif_kapasitas" class="block text-sm font-medium text-gray-700">
                Tarif Kapasitas (Rp) <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                name="tarif_kapasitas" 
                id="tarif_kapasitas" 
                value="{{ old('tarif_kapasitas') }}" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" 
                required
                maxlength="9"
                onkeypress="return isNumber(event)"
                oninput="validateNumberInput(this)"
                placeholder="Masukkan angka saja"
            >
            @error('tarif_kapasitas')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input: Deskripsi Kapasitas -->
        <div>
            <label for="deskripsi_kapasitas" class="block text-sm font-medium text-gray-700">
                Deskripsi
            </label>
            <textarea name="deskripsi_kapasitas" id="deskripsi_kapasitas" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">{{ old('deskripsi_kapasitas') }}</textarea>
        </div>

        <!-- Input: Tampil di E-Katalog -->
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Tampil di e-Katalog? <span class="text-red-500">*</span>
            </label>
            <div class="mt-1">
                <label class="inline-flex items-center">
                    <input type="radio" name="tampil_ekatalog" value="1" class="form-radio" {{ old('tampil_ekatalog') == '1' ? 'checked' : '' }} required>
                    <span class="ml-2">Ya</span>
                </label>
                <label class="inline-flex items-center ml-6">
                    <input type="radio" name="tampil_ekatalog" value="0" class="form-radio" {{ old('tampil_ekatalog') == '0' ? 'checked' : '' }} required>
                    <span class="ml-2">Tidak</span>
                </label>
            </div>
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
                <p class="text-sm text-gray-600" id="modalMessage">Kapasitas Internet sudah ada</p>
            </div>
            <div class="mt-5 flex justify-end">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                    Oke
                </button>
            </div>
        </div>
    </div>

    <script>
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

        // Duplicate check functions
        function showModal(message) {
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('duplicateModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('duplicateModal').classList.add('hidden');
        }

        // Show modal if there's error from server
        @if($errors->has('besar_kapasitas'))
            showModal("{{ $errors->first('besar_kapasitas') }}");
        @endif

        // Prevent paste non-numeric characters
        document.getElementById('besar_kapasitas').addEventListener('paste', function(e) {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text/plain').replace(/[^0-9]/g, '');
            document.execCommand('insertText', false, pasteData);
        });

        document.getElementById('tarif_kapasitas').addEventListener('paste', function(e) {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text/plain').replace(/[^0-9]/g, '');
            document.execCommand('insertText', false, pasteData);
        });

        // Final validation before submit
        function validateForm() {
            const besarKapasitas = document.getElementById('besar_kapasitas').value;
            const tarifKapasitas = document.getElementById('tarif_kapasitas').value;
            
            if (!/^\d+$/.test(besarKapasitas)) {
                showModal('Besar Kapasitas harus berupa angka');
                return false;
            }
            
            if (!/^\d+$/.test(tarifKapasitas)) {
                showModal('Tarif Kapasitas harus berupa angka');
                return false;
            }
            
            return true;
        }
    </script>
</x-layout>