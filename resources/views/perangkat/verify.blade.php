{{-- resources/views/kapasitas/verify.blade.php --}}
<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    {{-- Button Tambah --}}
    <div class="mb-8 mt-8">
        <x-button.tambah href="{{ route('kapasitas.create') }}" label="Tambah Kapasitas Internet" />
    </div>

    {{-- Tombol Navigasi --}}
    <div class="mb-6 flex items-center space-x-2 text-gray-700 text-sm">
        <a href="{{ route('perangkat.index') }}" 
           class="{{ request()->routeIs('perangkat.index') ? 'text-blue-500 font-semibold underline' : 'font-semibold' }}">
            Daftar
        </a>
        <span>|</span>
        <a href="{{ route('perangkat.verify') }}" 
           class="{{ request()->routeIs('perangkat.verify') ? 'text-blue-500 font-semibold underline' : 'font-semibold' }}">
            Menunggu Verifikasi
        </a>
    </div>

    {{-- Panggil Partial View Filter --}}
    @include('perangkat.partials.filter', ['produk' => $produk])

    <!-- Tabel Kapasitas Menunggu Verifikasi -->
    @include('perangkat.partials.table', ['perangkat' => $perangkat, 'is_verify' => true])

    <!-- Modal Tolak -->
    @foreach($perangkat as $item)
        <div id="modal-tolak-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-sm mx-4 p-6">
                <h3 class="text-lg font-semibold mb-4">Konfirmasi Penolakan</h3>
                <form action="{{ route('perangkat.tolak', $item->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700">Alasan Penolakan</label>
                        <textarea 
                            name="alasan_penolakan" 
                            id="alasan_penolakan_{{ $item->id }}" 
                            rows="3" 
                            maxlength="150" 
                            oninput="updateCharCount({{ $item->id }})" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-3 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Masukkan alasan penolakan (maksimal 150 karakter)"></textarea>
                        <p id="charCount_{{ $item->id }}" class="text-sm text-gray-500">150/150 karakter tersisa</p>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="mt-6 space-y-2">
                        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Kirim Alasan Penolakan</button>
                        <button type="button" onclick="closeModal('modal-tolak-{{ $item->id }}')" class="w-full bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <!-- JavaScript untuk Modal -->
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function updateCharCount(itemId) {
            const textarea = document.getElementById(`alasan_penolakan_${itemId}`);
            const maxLength = 150;
            const currentLength = textarea.value.length;
            const remaining = maxLength - currentLength;

            const countElement = document.getElementById(`charCount_${itemId}`);
            countElement.textContent = `${remaining}/250 karakter tersisa`;
        }
    </script>
</x-layout>