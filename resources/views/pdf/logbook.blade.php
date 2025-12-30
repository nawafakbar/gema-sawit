<!DOCTYPE html>
<html>
<head>
    <title>Laporan ISPO</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Aktivitas Harian (ISPO)</h2>
    <p>Tanggal Cetak: {{ date('d M Y') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kegiatan</th>
                <th>Bahan</th>
                <th>Dosis</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td>{{ date('d/m/Y', strtotime($log->date)) }}</td>
                <td>{{ $log->activity_type }}</td>
                <td>{{ $log->material }}</td>
                <td>{{ $log->dose }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>