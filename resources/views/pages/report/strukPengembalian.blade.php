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

        .info p {
            margin: 4px 0;
        }

        .footer {
            margin-top: 60px;
            font-size: 11pt;
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
        <p><strong>Nama Peminjam :</strong> {{ $borrowing->nama_peminjam }}</p>
        <p><strong>Tanggal Pinjam :</strong> {{ \Carbon\Carbon::parse($borrowing->tanggal_pinjam)->format('d F Y') }}
        </p>
        <p><strong>Nama Program :</strong> {{ $borrowing->keperluan }}</p>
        <p><strong>Operator Peralatan :</strong> {{ $borrowing->user->name ?? '-' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA BARANG</th>
                <th>MERK</th>
                <th>JUMLAH</th>
                <th>TGL. KEMBALI</th>
                <th>KONDISI</th>
                <th>KET</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowing->borrowingDetails as $i => $detail)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $detail->tool->nama_alat ?? '-' }}</td>
                    <td>{{ $detail->tool->merk ?? '-' }}</td>
                    <td>{{ $detail->jumlah_pinjam }}</td>
                    <td>{{ \Carbon\Carbon::parse($borrowing->tanggal_pinjam)->format('d F Y') }}
                    </td>
                    <td>{{ $detail->borrowing->kondisi ?? '-' }}</td>
                    <td>{{ $detail->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Operator Pengembalian :</strong> {{ auth()->user()->name ?? '-' }}</p>
        <p><strong>Catatan :</strong> {{ $borrowing->catatan ?? '-' }}</p>
    </div>
</body>

</html>
