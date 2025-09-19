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

        .info-row {
            display: flex;
            margin: 5px 0;
        }

        .info-label {
            min-width: 190px;
            display: inline-block;
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
        <div class="info-row">
            <span class="info-label"><strong>Nama Peminjam</strong></span>
            <span>: {{ $borrowing->nama_peminjam }}</span>
        </div>
        <div class="info-row">
            <span class="info-label"><strong>Tanggal Pinjam</strong></span>
            <span>: {{ \Carbon\Carbon::parse($borrowing->tanggal_pinjam)->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label"><strong>Tanggal Kembali (Rencana)</strong></span>
            <span>: {{ \Carbon\Carbon::parse($borrowing->tanggal_kembali_rencana)->format('d/m/Y') }}</span>
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
                <th>KONDISI AWAL</th>
                <th>KETERANGAN</th>
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
