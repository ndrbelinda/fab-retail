<!DOCTYPE html>
<html>
<head>
    <title>Perangkat {{ ucfirst($status) }}</title>
</head>
<body>
    <h1>Perangkat {{ ucfirst($status) }}</h1>

    @if ($status === 'diajukan')
        <p>Perangkat dengan ID {{ $perangkat->id }} telah diajukan.</p>
    @elseif ($status === 'diverifikasi')
        <p>Perangkat dengan ID {{ $perangkat->id }} telah disetujui.</p>
    @elseif ($status === 'ditolak')
        <p>Perangkat dengan ID {{ $perangkat->id }} telah ditolak.</p>
        <p>Alasan Penolakan: {{ $keterangan }}</p>
    @elseif ($status === 'dikembalikan')
        <p>Perangkat dengan ID {{ $perangkat->id }} telah dikembalikan ke draft.</p>
        <p>Keterangan: {{ $keterangan }}</p>
    @endif

    <p>Detail Perangkat:</p>
    <ul>
        <li>Produk: {{ $perangkat->produk->nama_produk }}</li>
        <li>Jenis Perangkat: {{ $perangkat->jenis_perangkat }}</li>
        <li>Tarif: {{ $perangkat->tarif_perangkat }}</li>
    </ul>
</body>
</html>