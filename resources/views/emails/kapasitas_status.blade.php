<!DOCTYPE html>
<html>
<head>
    <title>Kapasitas {{ ucfirst($status) }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4a6fa5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .status-box {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-weight: bold;
        }
        .status-diajukan {
            background-color: #e3f2fd;
            color: #1565c0;
            border-left: 4px solid #1565c0;
        }
        .status-diverifikasi {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #2e7d32;
        }
        .status-ditolak {
            background-color: #ffebee;
            color: #c62828;
            border-left: 4px solid #c62828;
        }
        .status-dikembalikan {
            background-color: #fff8e1;
            color: #ff8f00;
            border-left: 4px solid #ff8f00;
        }
        .details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
        }
        .details li {
            margin-bottom: 8px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4a6fa5;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Kapasitas {{ ucfirst($status) }}</h1>
    </div>
    
    <div class="content">
        <div class="status-box status-{{ $status }}">
            @if ($status === 'diajukan')
                <p>Kapasitas #{{ $kapasitas->id }} telah diajukan untuk ditinjau.</p>
            @elseif ($status === 'diverifikasi')
                <p>Kapasitas #{{ $kapasitas->id }} telah disetujui.</p>
            @elseif ($status === 'ditolak')
                <p>Kapasitas #{{ $kapasitas->id }} telah ditolak.</p>
                <p><strong>Alasan Penolakan:</strong> {{ $keterangan }}</p>
            @elseif ($status === 'dikembalikan')
                <p>Kapasitas #{{ $kapasitas->id }} telah dikembalikan menjadi draft.</p>
                <p><strong>Keterangan:</strong> {{ $keterangan }}</p>
            @endif
        </div>

        <h3>Detail Kapasitas:</h3>
        <div class="details">
            <ul>
                <li><strong>Produk:</strong> {{ $kapasitas->produk->nama_produk }}</li>
                <li><strong>Besar Kapasitas:</strong> {{ $kapasitas->besar_kapasitas }}</li>
                <li><strong>Tarif:</strong> {{ $kapasitas->tarif_kapasitas }}</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>Ini adalah notifikasi otomatis. Mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} PT XYZ. All rights reserved.</p>
        </div>
    </div>
</body>
</html>