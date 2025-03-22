<!DOCTYPE html>
<html>
<head>
    <title>FAQ {{ ucfirst($status) }}</title>
</head>
<body>
    <h1>FAQ {{ ucfirst($status) }}</h1>

    @if ($status === 'diajukan')
        <p>FAQ dengan ID {{ $faq->id }} telah diajukan.</p>
    @elseif ($status === 'diverifikasi')
        <p>FAQ dengan ID {{ $faq->id }} telah disetujui.</p>
    @elseif ($status === 'ditolak')
        <p>FAQ dengan ID {{ $faq->id }} telah ditolak.</p>
        <p>Alasan Penolakan: {{ $keterangan }}</p>
    @elseif ($status === 'dikembalikan')
        <p>FAQ dengan ID {{ $faq->id }} telah dikembalikan ke draft.</p>
        <p>Keterangan: {{ $keterangan }}</p>
    @endif

    <p>Detail faq:</p>
    <ul>
        <li>Produk: {{ $faq->produk->nama_produk }}</li>
        <li>Pertanyaan: {{ $faq->pertanyaan }}</li>
        <li>Jawaban: {{ $faq->jawaban }}</li>
    </ul>
</body>
</html>