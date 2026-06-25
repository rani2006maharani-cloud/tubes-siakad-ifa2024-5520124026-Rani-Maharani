{{-- resources/views/dosen/export-pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Dosen</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { text-align: center; color: #1a1a2e; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th { background: #1a1a2e; color: white; padding: 10px; text-align: left; }
        table td { padding: 8px 10px; border-bottom: 1px solid #ddd; }
        .text-center { text-align: center; }
        .footer { margin-top: 20px; text-align: center; color: #6c757d; font-size: 12px; }
    </style>
</head>
<body>
    <h1>📋 DAFTAR DOSEN</h1>
    <p style="text-align: center;">Total: {{ $dosens->count() }} Dosen</p>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>NIDN</th>
                <th>Email</th>
                <th>JK</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dosens as $index => $dosen)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $dosen->kode_dosen }}</td>
                <td>{{ $dosen->nama_dosen }}</td>
                <td>{{ $dosen->nidn }}</td>
                <td>{{ $dosen->email }}</td>
                <td>{{ $dosen->jenis_kelamin == 'L' ? 'L' : 'P' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        Dicetak: {{ date('d F Y H:i:s') }}
    </div>
</body>
</html>