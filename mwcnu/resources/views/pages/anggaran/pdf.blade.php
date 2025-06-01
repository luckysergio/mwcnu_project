<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Anggaran {{ $prokerName }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">
        Laporan Anggaran<br>
        <small style="font-weight: normal;">Program Kerja: {{ $prokerName }}</small>
    </h2>

    <table>
        <thead>
            <tr>
                <th>Pendana</th>
                <th>Jumlah</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($anggarans as $item)
                <tr>
                    <td>{{ $item->pendana }}</td>
                    <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td>{{ $item->catatan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
