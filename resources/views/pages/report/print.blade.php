<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Peminjaman</title>
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
            font-size: 11pt;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3>{{ config('app.name') }}</h3>
        <p><strong>RIWAYAT PEMINJAMAN</strong></p>
        <p>DIVISI TEKNIK LPP TVRI STASIUN BALI</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA BARANG</th>
                <th>NAMA PEMINJAM</th>
                <th>NAMA PROGRAM</th>
                <th colspan="3">PINJAM</th>
                <th colspan="3">PENGEMBALIAN</th>
                <th>KET</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>TGL</th>
                <th>OPR</th>
                <th>KONDISI</th>
                <th>TGL</th>
                <th>OPR</th>
                <th>KONDISI</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($history as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        {{ optional($item->borrowingDetails->first()->tool)->nama_alat ?? '-' }}
                    </td>
                    <td>{{ $item->nama_peminjam ?? '-' }}</td>
                    <td>{{ $item->keperluan ?? '-' }}</td>
                    <td>{{ $item->tanggal_pinjam ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') : '-' }}
                    </td>
                    <td>{{ $item->operatorPinjam->name ?? '-' }}</td>
                    <td>{{ optional($item->borrowingDetails->first())->kondisi_awal ?? '-' }}</td>
                    <td>{{ $item->tanggal_kembali_aktual ? \Carbon\Carbon::parse($item->tanggal_kembali_aktual)->format('d/m/Y') : '-' }}
                    </td>
                    <td>{{ $item->operatorKembali->name ?? '-' }}</td>
                    <td>{{ optional($item->borrowingDetails->first())->kondisi_akhir ?? '-' }}</td>
                    <td>{{ $item->borrowingDetails->first()->keterangan_akhir ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
