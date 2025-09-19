<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk Peminjaman</title>
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
            display: flex;
            justify-content: space-between;
            font-size: 11pt;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3>{{ config('app.name') }}</h3>
        <p><strong>LAPORAN PEMINJAMAN</strong></p>
        <p>DIVISI TEKNIK LPP TVRI STASIUN BALI</p>
    </div>

    <div class="info">
        <p><strong>Nama Peminjam :</strong> {{ $borrowing->nama_peminjam }}</p>
        <p><strong>Tanggal Pinjam :</strong> {{ \Carbon\Carbon::parse($borrowing->tanggal_pinjam)->format('d/m/Y') }}
        </p>
        <p><strong>Tanggal Kembali (Rencana) :</strong>
            {{ \Carbon\Carbon::parse($borrowing->tanggal_kembali_rencana)->format('d/m/Y') }}</p>
        <p><strong>Nama Program :</strong> {{ $borrowing->keperluan }}</p>
        <p><strong>Operator Peralatan :</strong> {{ $borrowing->operatorPinjam->name ?? '-' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA BARANG</th>
                <th>MERK</th>
                <th>JUMLAH</th>
                <th>KONDISI AWAL</th>
                <th>KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowing->borrowingDetails as $i => $detail)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $detail->tool->nama_alat ?? '-' }}</td>
                    <td>{{ $detail->tool->merk ?? '-' }}</td>
                    <td>{{ $detail->jumlah_pinjam }}</td>
                    <td>{{ $detail->kondisi_awal ?? '-' }}</td>
                    <td>{{ $detail->keterangan_awal ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- <div class="footer" style="margin-top:80px;">
        <table style="width:100%; border:none;">
            <tr>
                <td style="border:none; width:50%;"></td> <!-- kosong -->
                <td style="border:none; text-align:center; width:50%;">
                    <p>Denpasar, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                    <p><strong>Operator</strong></p>
                    <br><br><br>
                    <p>_________________________</p>
                </td>
            </tr>
        </table>
    </div> --}}

</body>

</html>
