<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #eee; }
    </style>
</head>
<body>

<h3>Rekap Penanganan Keluhan</h3>

<table>
    <thead>
        <tr>
            <th>No Tiket</th>
            <th>Tanggal</th>
            <th>Penghuni</th>
            <th>Departemen</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $d)
        <tr>
            <td>{{ $d['tiket'] }}</td>
            <td>{{ $d['tanggal'] }}</td>
            <td>{{ $d['nama'] }}</td>
            <td>{{ $d['departemen'] }}</td>
            <td>{{ $d['status'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>