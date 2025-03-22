<div>
    <!-- Button Filter -->
    <button onclick="openFilterModal()" class="text-[10pt] bg-orange-500 text-white px-4 py-2 rounded-md mb-6 hover:bg-orange-700">
        Filter
    </button>

    <!-- Modal Filter -->
    <div id="filterModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <!-- Modal Content -->
            <div class="mt-3 text-start">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Filter</h3>
                <div class="mt-2 py-3">
                    <!-- Filter by Produk -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Produk</label>
                        @foreach($produk as $item)
                            <div class="mt-1">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="produk[]" value="{{ $item->nama_produk }}" 
                                           class="form-checkbox"
                                           {{ in_array($item->nama_produk, request()->query('produk', [])) ? 'checked' : '' }}>
                                    <span class="ml-2">{{ $item->nama_produk }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Filter by Tarif -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tarif</label>
                        <div class="mt-1">
                            <label class="inline-flex items-center">
                                <input type="radio" name="tarif" value="terendah" class="form-radio"
                                       {{ request()->query('tarif') === 'terendah' ? 'checked' : '' }}>
                                <span class="ml-2">Terendah</span>
                            </label>
                            <label class="inline-flex items-center ml-6">
                                <input type="radio" name="tarif" value="tertinggi" class="form-radio"
                                       {{ request()->query('tarif') === 'tertinggi' ? 'checked' : '' }}>
                                <span class="ml-2">Tertinggi</span>
                            </label>
                        </div>
                    </div>

                    <!-- Filter by Kapasitas -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Kapasitas</label>
                        <div class="mt-1">
                            <label class="inline-flex items-center">
                                <input type="radio" name="kapasitas" value="terendah" class="form-radio"
                                       {{ request()->query('kapasitas') === 'terendah' ? 'checked' : '' }}>
                                <span class="ml-2">Terendah</span>
                            </label>
                            <label class="inline-flex items-center ml-6">
                                <input type="radio" name="kapasitas" value="tertinggi" class="form-radio"
                                       {{ request()->query('kapasitas') === 'tertinggi' ? 'checked' : '' }}>
                                <span class="ml-2">Tertinggi</span>
                            </label>
                        </div>
                    </div>

                    <!-- Filter by Waktu Pembuatan -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Waktu Pembuatan</label>
                        <div class="mt-1">
                            <label class="inline-flex items-center">
                                <input type="radio" name="waktu" value="terlama" class="form-radio"
                                       {{ request()->query('waktu') === 'terlama' ? 'checked' : '' }}>
                                <span class="ml-2">Terlama</span>
                            </label>
                            <label class="inline-flex items-center ml-6">
                                <input type="radio" name="waktu" value="terbaru" class="form-radio"
                                       {{ request()->query('waktu') === 'terbaru' ? 'checked' : '' }}>
                                <span class="ml-2">Terbaru</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="items-center py-3">
                    <button onclick="closeFilterModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Tutup
                    </button>
                    <button onclick="applyFilters()" class="ml-3 px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        Terapkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openFilterModal() {
        document.getElementById('filterModal').classList.remove('hidden');
    }

    function closeFilterModal() {
        document.getElementById('filterModal').classList.add('hidden');
    }

    function applyFilters() {
        // Ambil semua checkbox produk
        const allProduk = Array.from(document.querySelectorAll('input[name="produk[]"]'));
        const selectedProduk = allProduk
            .filter(el => el.checked)
            .map(el => el.value);

        const selectedTarif = document.querySelector('input[name="tarif"]:checked')?.value;
        const selectedKapasitas = document.querySelector('input[name="kapasitas"]:checked')?.value;
        const selectedWaktu = document.querySelector('input[name="waktu"]:checked')?.value;

        // Bangun URL baru
        const url = new URL(window.location.href);

        // Hapus semua parameter filter sebelumnya
        url.searchParams.delete('produk[]');
        url.searchParams.delete('tarif');
        url.searchParams.delete('kapasitas');
        url.searchParams.delete('waktu');

        // Tambahkan parameter filter baru jika ada
        if (selectedProduk.length > 0) {
            selectedProduk.forEach(produk => url.searchParams.append('produk[]', produk));
        }

        if (selectedTarif) {
            url.searchParams.set('tarif', selectedTarif);
        }

        if (selectedKapasitas) {
            url.searchParams.set('kapasitas', selectedKapasitas);
        }

        if (selectedWaktu) {
            url.searchParams.set('waktu', selectedWaktu);
        }

        // Redirect ke URL dengan parameter filter
        window.location.href = url.toString();
    }

    // Uncheck radio button jika diklik dua kali
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('click', function () {
            // Simpan referensi radio yang terakhir kali diklik
            if (this.checked) {
                this.previousChecked = !this.previousChecked;
                if (!this.previousChecked) {
                    this.checked = false;
                }
            } else {
                this.previousChecked = true;
            }
        });
    });
</script>


