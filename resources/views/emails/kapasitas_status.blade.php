<!DOCTYPE html>
<html>
<head>
    <title>Kapasitas {{ ucfirst($status) }}</title>
</head>
<body>
    <h1>Kapasitas {{ ucfirst($status) }}</h1>

    @if ($status === 'diajukan')
        <p>Kapasitas dengan ID {{ $kapasitas->id }} telah diajukan.</p>
    @elseif ($status === 'diverifikasi')
        <p>Kapasitas dengan ID {{ $kapasitas->id }} telah disetujui.</p>
    @elseif ($status === 'ditolak')
        <p>Kapasitas dengan ID {{ $kapasitas->id }} telah ditolak.</p>
        <p>Alasan Penolakan: {{ $keterangan }}</p>
    @elseif ($status === 'dikembalikan')
        <p>Kapasitas dengan ID {{ $kapasitas->id }} telah dikembalikan ke draft.</p>
        <p>Keterangan: {{ $keterangan }}</p>
    @endif

    <p>Detail Kapasitas:</p>
    <ul>
        <li>Produk: {{ $kapasitas->produk->nama_produk }}</li>
        <li>Besar Kapasitas: {{ $kapasitas->besar_kapasitas }}</li>
        <li>Tarif: {{ $kapasitas->tarif_kapasitas }}</li>
    </ul>
</body>
</html>