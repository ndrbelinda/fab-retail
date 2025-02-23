<div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded-lg shadow">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Produk</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Jumlah Kapasitas</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tarif</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Pricing</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Riwayat Pricing</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($kapasitas as $item)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item->produk->nama_produk }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item->besar_kapasitas }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">Rp{{ number_format($item->tarif_kapasitas, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        Rp{{ number_format($item->riwayatPricing->first()->pricing ?? $item->tarif_kapasitas, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="#" onclick="openModal('modal-riwayat-{{ $item->id }}')" class="text-blue-600 underline">Lihat Riwayat</a>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <button onclick="openModal('modal-ubah-{{ $item->id }}')" class="bg-blue-500 text-white px-2 py-1 text-xs rounded hover:bg-blue-600">Ubah</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Modal Riwayat Pricing --}}
@foreach($kapasitas as $item)
    <div id="modal-riwayat-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4 p-6" style="max-height: 80vh; overflow-y: auto;">
            <h3 class="text-lg font-semibold mb-4">Riwayat Pricing</h3>

            {{-- Informasi Kapasitas --}}
            <div class="space-y-2">
                <p><strong>Produk:</strong> {{ $item->produk->nama_produk }}</p>
                <p><strong>Besar Kapasitas:</strong> {{ $item->besar_kapasitas }}</p>
                <p><strong>Tarif:</strong> Rp{{ number_format($item->tarif_kapasitas, 0, ',', '.') }}</p>
            </div>

            {{-- Tabel Riwayat Pricing --}}
            <h4 class="text-md font-semibold mt-4 mb-2">Riwayat Perubahan Pricing</h4>
            <table class="min-w-full bg-gray-100 rounded-lg">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-700">Waktu</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-700">Pricing</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($item->riwayatPricing as $riwayat)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $riwayat->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">Rp{{ number_format($riwayat->pricing, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Tombol Tutup --}}
            <div class="mt-4">
                <button onclick="closeModal('modal-riwayat-{{ $item->id }}')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Tutup</button>
            </div>
        </div>
    </div>
@endforeach

{{-- Modal Ubah Pricing --}}
@foreach($kapasitas as $item)
    <div id="modal-ubah-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-sm mx-4 p-6">
            <h3 class="text-lg font-semibold mb-4">Ubah Pricing</h3>
            <form action="{{ route('pricing.kapasitas.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <label for="pricing" class="block text-sm font-medium text-gray-700">Nominal Pricing</label>
                    <input 
                        type="number" 
                        name="pricing" 
                        id="pricing-{{ $item->id }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 focus:border-blue-500 focus:ring-blue-500 @error('pricing') border-red-500 @enderror" 
                        required
                        min="{{ $item->tarif_kapasitas }}" 
                        value="{{ $item->riwayatPricing->first()->pricing ?? $item->tarif_kapasitas }}"
                    >
                    {{-- Pesan error --}}
                    @error('pricing')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-6 space-y-2">
                    <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
                    <button type="button" onclick="closeModal('modal-ubah-{{ $item->id }}')" class="w-full bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- JavaScript untuk Modal --}}
<script>
    function openModal(modalId) {
        console.log('Membuka modal:', modalId); // Debug
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        console.log('Menutup modal:', modalId); // Debug
        document.getElementById(modalId).classList.add('hidden');
    }

    // Validasi real-time saat input pricing diubah
    document.querySelectorAll('input[name="pricing"]').forEach(input => {
        input.addEventListener('input', function() {
            const tarif = parseFloat(this.getAttribute('min')); // Ambil nilai minimal (tarif)
            const pricing = parseFloat(this.value);

            const errorMessage = this.nextElementSibling; // Ambil elemen pesan error

            if (pricing < tarif) {
                errorMessage.textContent = 'Pricing tidak boleh kurang dari tarif.';
                errorMessage.classList.remove('hidden');
            } else {
                errorMessage.textContent = '';
                errorMessage.classList.add('hidden');
            }
        });
    });
</script>