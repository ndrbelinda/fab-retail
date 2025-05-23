{{-- resources/views/kapasitas/partials/table.blade.php --}}
<div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded-lg shadow">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Produk</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Besar Kapasitas (GB)</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tarif</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">E-Katalog</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Detail</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($kapasitas as $item)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item->produk->nama_produk }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item->besar_kapasitas }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">Rp{{ number_format($item->tarif_kapasitas, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item->tampil_ekatalog ? 'Ya' : 'Tidak' }}</td>
                    <td class="px-6 py-4 text-sm">
                        <a href="#" onclick="openModal('modal-detail-{{ $item->id }}')" class="text-blue-600 underline">Lihat Detail</a>
                    </td>
                    {{-- Status --}}
                    <td class="px-6 py-4 text-sm text-gray-900 flex space-x-2">
                        {{-- Status: Draft --}}
                        <div class="relative group">
                            <div class="w-4 h-4 flex items-center justify-center rounded-full {{ in_array($item->is_verified_kapasitas, ['draft', 'diajukan', 'diverifikasi']) ? 'bg-green-500' : 'bg-gray-300' }} text-white text-xs">
                                ✓
                            </div>
                            <div class="absolute hidden group-hover:block bg-black text-white text-xs px-2 py-1 rounded mt-2">
                                Draft
                            </div>
                        </div>

                        {{-- Status: Diajukan --}}
                        <div class="relative group">
                            <div class="w-4 h-4 flex items-center justify-center rounded-full {{ in_array($item->is_verified_kapasitas, ['diajukan', 'diverifikasi']) ? 'bg-green-500' : 'bg-gray-300' }} text-white text-xs">
                                ✓
                            </div>
                            <div class="absolute hidden group-hover:block bg-black text-white text-xs px-2 py-1 rounded mt-2">
                                Diajukan
                            </div>
                        </div>

                        {{-- Status: Diverifikasi --}}
                        <div class="relative group">
                            <div class="w-4 h-4 flex items-center justify-center rounded-full {{ $item->is_verified_kapasitas === 'diverifikasi' ? 'bg-green-500' : 'bg-gray-300' }} text-white text-xs">
                                ✓
                            </div>
                            <div class="absolute hidden group-hover:block bg-black text-white text-xs px-2 py-1 rounded mt-2">
                                Diverifikasi
                            </div>
                        </div>
                    </td>
                    {{-- Aksi --}}
                    <td class="px-6 py-4 text-sm text-gray-900">
                        @if(isset($is_verify))
                            {{-- Tombol untuk halaman verifikasi --}}
                            <form action="{{ route('kapasitas.terima', $item->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-500 text-white px-2 py-1 text-xs rounded hover:bg-green-600">Terima</button>
                            </form>
                            <button onclick="openModal('modal-tolak-{{ $item->id }}')" class="bg-red-500 text-white px-2 py-1 text-xs rounded hover:bg-red-600 ml-2">Tolak</button>
                        @else
                            {{-- Tombol untuk halaman daftar kapasitas --}}
                            @if($item->is_verified_kapasitas === 'draft')
                                <a href="{{ route('kapasitas.edit', $item->id) }}" class="bg-blue-500 text-white px-2 py-1 text-xs rounded hover:bg-blue-600">Ubah</a>
                                <button onclick="openModal('modal-hapus-{{ $item->id }}')" class="bg-red-500 text-white px-2 py-1 text-xs rounded hover:bg-red-600 ml-2">Hapus Draft</button>
                            @elseif($item->is_verified_kapasitas === 'diajukan')
                                <button class="bg-gray-500 text-white px-2 py-1 text-xs rounded cursor-not-allowed" disabled>
                                    Menunggu Diverifikasi
                                </button>
                            @elseif($item->is_verified_kapasitas === 'diverifikasi')
                                @if(auth()->check() && auth()->user()->role === 'avp')
                                    <form action="{{ route('kapasitas.kembalikan', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-yellow-500 text-white px-2 py-1 text-xs rounded hover:bg-yellow-600">Kembalikan</button>
                                    </form>
                                @endif
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination -->
{{-- <div class="mt-4 flex justify-center">
    {{ $kapasitas->links('vendor.pagination.tailwind') }}
</div> --}}

<!-- Pagination -->
<div class="mt-4">
    {{ $kapasitas->links() }}
</div>

{{-- Modal Detail --}}
@foreach($kapasitas as $item)
    <div id="modal-detail-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4 p-6" style="max-height: 80vh; overflow-y: auto;">
            <h3 class="text-lg font-semibold mb-4">Detail Kapasitas</h3>

            {{-- Informasi dari Input Form Create --}}
            <div class="space-y-2">
                <p><strong>Produk:</strong> {{ $item->produk->nama_produk }}</p>
                <p><strong>Besar Kapasitas:</strong> {{ $item->besar_kapasitas }}</p>
                <p><strong>Tarif:</strong> Rp{{ number_format($item->tarif_kapasitas, 0, ',', '.') }}</p>
                <p><strong>Deskripsi:</strong> {{ $item->deskripsi_kapasitas }}</p>
                <p><strong>E-Katalog:</strong> {{ $item->tampil_ekatalog ? 'Ya' : 'Tidak' }}</p>
            </div>

            {{-- Tabel Riwayat Status --}}
            <h4 class="text-md font-semibold mt-4 mb-2">Riwayat Status</h4>
            <table class="min-w-full bg-gray-100 rounded-lg">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-700">Waktu</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-700">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($item->riwayat->sortByDesc('created_at') as $riwayat)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">
                                @if($riwayat->status === 'draft')
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Draft</span>
                                @elseif($riwayat->status === 'diajukan')
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Diajukan</span>
                                @elseif($riwayat->status === 'diverifikasi')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Diverifikasi</span>
                                @elseif($riwayat->status === 'ditolak')
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $riwayat->waktu }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $riwayat->keterangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Tombol Tutup --}}
            <div class="mt-4">
                <button onclick="closeModal('modal-detail-{{ $item->id }}')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Tutup</button>
            </div>
        </div>
    </div>
@endforeach

{{-- Modal Hapus --}}
@foreach($kapasitas as $item)
    <div id="modal-hapus-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-sm mx-4 p-6">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h3>
            <p class="mb-4">Apakah Anda yakin ingin menghapus kapasitas ini?</p>

            <form action="{{ route('kapasitas.destroy', $item->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('modal-hapus-{{ $item->id }}')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</button>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Hapus</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- JavaScript untuk Modal --}}
<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>