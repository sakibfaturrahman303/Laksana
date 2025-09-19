<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk Pengembalian</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 30px;
            font-size: 12pt;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h3,
        .header p {
            margin: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        .info {
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            margin: 4px 0;
        }

        .info-label {
            min-width: 150px;
            display: inline-block;
        }

        .footer {
            margin-top: 60px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3>{{ config('app.name') }}</h3>
        <p><strong>LAPORAN PENGEMBALIAN</strong></p>
        <p>DIVISI TEKNIK LPP TVRI STASIUN BALI</p>
    </div>

    <div class="info">
        <div class="info-row">
            <span class="info-label"><strong>Nama Peminjam</strong></span>
            <span>: {{ $borrowing->nama_peminjam }}</span>
        </div>
        <div class="info-row">
            <span class="info-label"><strong>Tanggal Pinjam</strong></span>
            <span>: {{ \Carbon\Carbon::parse($borrowing->tanggal_pinjam)->format('d F Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label"><strong>Nama Program</strong></span>
            <span>: {{ $borrowing->keperluan }}</span>
        </div>
        <div class="info-row">
            <span class="info-label"><strong>Operator Peralatan</strong></span>
            <span>: {{ $borrowing->operatorPinjam->name ?? '-' }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>KATEGORI</th>
                <th>MERK</th>
                <th>NAMA BARANG</th>
                <th>JUMLAH</th>
                <th>TGL. KEMBALI</th>
                <th>KONDISI AWAL</th>
                <th>KONDISI AKHIR</th>
                <th>KET</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowing->borrowingDetails as $i => $detail)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $detail->tool->category->nama_kategori ?? '-' }}</td>
                    <td>{{ $detail->tool->merk ?? '-' }}</td>
                    <td>{{ $detail->tool->nama_alat ?? '-' }}</td>
                    <td>{{ $detail->jumlah_pinjam }}</td>
                    <td>{{ \Carbon\Carbon::parse($borrowing->tanggal_pinjam)->format('d F Y') }}
                    </td>
                    <td>{{ $detail->kondisi_awal ?? '-' }}</td>
                    <td>{{ $detail->kondisi_akhir ?? '-' }}</td>
                    <td>{{ $detail->keterangan_akhir ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Operator Pengembalian :</strong> {{ $borrowing->operatorKembali->name ?? '-' }}</p>
        <p><strong>Catatan :</strong> {{ $borrowing->catatan ?? '-' }}</p>
    </div>
</body>

</html>
