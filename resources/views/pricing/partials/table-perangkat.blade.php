<div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded-lg shadow">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Produk</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Jenis Perangkat</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tarif</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Pricing</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Riwayat Pricing</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($perangkat as $item)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item->produk->nama_produk }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item->jenis_perangkat }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">Rp{{ number_format($item->tarif_perangkat, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        Rp{{ number_format($item->riwayatPricingPerangkat->sortByDesc('created_at')->first()->pricing ?? $item->tarif_perangkat, 0, ',', '.') }}
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

{{-- Modal Riwayat Pricing Perangkat --}}
@foreach($perangkat as $item)
    <div id="modal-riwayat-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4 p-6" style="max-height: 80vh; overflow-y: auto;">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-semibold">Riwayat Pricing Perangkat</h3>
                <button onclick="closeModal('modal-riwayat-{{ $item->id }}')" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Informasi Perangkat --}}
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Produk</p>
                        <p class="text-sm text-gray-900">{{ $item->produk->nama_produk }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Jenis Perangkat</p>
                        <p class="text-sm text-gray-900">{{ $item->jenis_perangkat }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tarif</p>
                        <p class="text-sm text-gray-900">Rp{{ number_format($item->tarif_perangkat, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Tabel Riwayat Pricing --}}
            <h4 class="text-md font-semibold mb-3">Riwayat Perubahan Pricing</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Waktu</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Pricing</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Dokumen Pendukung</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($item->riwayatPricingPerangkat->sortByDesc('created_at') as $riwayat)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $riwayat->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    Rp{{ number_format($riwayat->pricing, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    @if($riwayat->dokumen)
                                        <a href="{{ asset('storage/' . $riwayat->dokumen) }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800 hover:underline">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            Lihat Dokumen
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Tombol Tutup --}}
            <div class="mt-6 flex justify-end">
                <button onclick="closeModal('modal-riwayat-{{ $item->id }}')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endforeach

{{-- Modal Ubah Pricing Perangkat --}}
@foreach($perangkat as $item)
    <div id="modal-ubah-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 p-6">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-semibold">Ubah Pricing Perangkat</h3>
                <button onclick="closeModal('modal-ubah-{{ $item->id }}')" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('pricing.perangkat.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-5">
                    {{-- Input Pricing --}}
                    <div>
                        <label for="pricing-{{ $item->id }}" class="block text-sm font-medium text-gray-700 mb-1">Nominal Pricing</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input 
                                type="number" 
                                name="pricing" 
                                id="pricing-{{ $item->id }}" 
                                class="block w-full pl-10 pr-12 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('pricing') border-red-500 @enderror" 
                                required
                                min="{{ $item->tarif_perangkat }}" 
                                value="{{ $item->riwayatPricingPerangkat->sortByDesc('created_at')->first()->pricing ?? $item->tarif_perangkat }}"
                                oninput="limitInputLength(this, 9)"
                            >
                        </div>
                        <div id="error-message-{{ $item->id }}" class="text-sm text-red-600 mt-1 hidden">
                            Maksimal 9 digit angka yang diperbolehkan.
                        </div>
                        @error('pricing')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Dokumen --}}
                    <div>
                        <label for="dokumen-{{ $item->id }}" class="block text-sm font-medium text-gray-700 mb-1">Dokumen Pendukung</label>
                        <div class="mt-1 flex items-center">
                            <label for="dokumen-{{ $item->id }}" class="cursor-pointer">
                                <div class="flex items-center px-4 py-2 bg-white rounded-md border border-gray-300 shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    Pilih File PDF
                                </div>
                                <input 
                                    type="file" 
                                    name="dokumen" 
                                    id="dokumen-{{ $item->id }}" 
                                    class="sr-only"
                                    accept=".pdf"
                                >
                            </label>
                            <span id="file-name-{{ $item->id }}" class="ml-3 text-sm text-gray-500">Tidak ada file dipilih</span>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Format: PDF (maks. 2MB)</p>
                        @error('dokumen')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('modal-ubah-{{ $item->id }}')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- JavaScript untuk Modal, Validasi Input, dan File Upload --}}
<script>
    // Fungsi untuk membuka modal
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    // Fungsi untuk menutup modal
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Fungsi untuk membatasi panjang input
    function limitInputLength(input, maxLength) {
        const errorMessage = document.getElementById(`error-message-${input.id.split('-')[1]}`);

        if (input.value.length > maxLength) {
            input.value = input.value.slice(0, maxLength);
            errorMessage.classList.remove('hidden');
        } else {
            errorMessage.classList.add('hidden');
        }
    }

    // Validasi real-time saat input pricing diubah
    document.querySelectorAll('input[name="pricing"]').forEach(input => {
        input.addEventListener('input', function() {
            const tarif = parseFloat(this.getAttribute('min'));
            const pricing = parseFloat(this.value) || 0;
            const errorMessage = this.nextElementSibling;

            if (pricing < tarif) {
                errorMessage.textContent = 'Pricing tidak boleh kurang dari tarif.';
                errorMessage.classList.remove('hidden');
            } else {
                errorMessage.textContent = '';
                errorMessage.classList.add('hidden');
            }
        });
    });

    // Menampilkan nama file yang dipilih
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : 'Tidak ada file dipilih';
            document.getElementById(`file-name-${this.id.split('-')[1]}`).textContent = fileName;

            // Validasi ukuran file
            const maxSize = 2 * 1024 * 1024; // 2MB
            const file = this.files[0];
            let errorElement = this.nextElementSibling;
            
            // Cari elemen error atau buat baru
            while (errorElement && !errorElement.classList.contains('text-red-600')) {
                errorElement = errorElement.nextElementSibling;
            }
            
            if (!errorElement) {
                errorElement = document.createElement('p');
                errorElement.className = 'mt-1 text-sm text-red-600';
                this.parentNode.insertBefore(errorElement, this.nextElementSibling.nextElementSibling);
            }

            if (file && file.size > maxSize) {
                errorElement.textContent = 'Ukuran file melebihi 2MB';
                this.value = '';
                document.getElementById(`file-name-${this.id.split('-')[1]}`).textContent = 'Tidak ada file dipilih';
            } else {
                errorElement.textContent = '';
            }
        });
    });

    // Tutup modal saat mengklik area luar modal
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('bg-black')) {
            const modals = document.querySelectorAll('[id^="modal-"]');
            modals.forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        }
    });
</script>