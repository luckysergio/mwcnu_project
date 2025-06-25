<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Program Kerja</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            margin: 20px;
        }

        h1,
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            padding: 6px 10px;
            border: 1px solid #999;
        }

        .foto-grid {
            margin-top: 10px;
        }

        .foto-grid img {
            width: 140px;
            height: auto;
            margin: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>

    <h1>Laporan Program Kerja</h1>
    <h2>{{ $laporan->proker->judul }}</h2>

    <table>
        <tr>
            <th>Judul Proker</th>
            <td>{{ $laporan->proker->judul }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ ucfirst($laporan->proker->status) }}</td>
        </tr>
        <tr>
            <th>Catatan Laporan</th>
            <td>{{ $laporan->catatan ?? '-' }}</td>
        </tr>
    </table>

    <h3>Detail Jadwal</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kegiatan</th>
                <th>Tanggal</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan->proker->jadwalProker->details as $i => $detail)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $detail->kegiatan }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($detail->tanggal_mulai)->format('d/m/Y') }} -
                        {{ \Carbon\Carbon::parse($detail->tanggal_selesai)->format('d/m/Y') }}
                    </td>
                    <td>{{ $detail->catatan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
