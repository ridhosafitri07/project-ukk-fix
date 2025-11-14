<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengaduan SAPRAS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
        }
        .header p {
            margin: 3px 0;
            font-size: 10px;
        }
        .filter-info {
            margin-bottom: 15px;
            padding: 8px;
            background-color: #f3f4f6;
            border-left: 3px solid #4F46E5;
        }
        .filter-info p {
            margin: 3px 0;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #4F46E5;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }
        td {
            padding: 6px 5px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .status-diajukan { background-color: #fef3c7; color: #92400e; }
        .status-disetujui { background-color: #dbeafe; color: #1e40af; }
        .status-ditolak { background-color: #fee2e2; color: #991b1b; }
        .status-diproses { background-color: #e0e7ff; color: #3730a3; }
        .status-selesai { background-color: #d1fae5; color: #065f46; }
        .footer {
            margin-top: 20px;
            font-size: 9px;
            text-align: right;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN DATA PENGADUAN SAPRAS</h2>
        <p>Tanggal Export: {{ $tanggal_export }}</p>
    </div>

    @if(!empty(array_filter($filters)))
    <div class="filter-info">
        <strong>Filter Aktif:</strong>
        @if($filters['status'])
            <p>• Status: <strong>{{ $filters['status'] }}</strong></p>
        @endif
        @if($filters['tanggal_dari'] || $filters['tanggal_sampai'])
            <p>• Tanggal: 
                <strong>{{ $filters['tanggal_dari'] ?? '...' }}</strong> s/d 
                <strong>{{ $filters['tanggal_sampai'] ?? '...' }}</strong>
            </p>
        @endif
        @if($filters['lokasi'])
            <p>• Lokasi: <strong>{{ $filters['lokasi'] }}</strong></p>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="8%">ID</th>
                <th width="10%">Tanggal</th>
                <th width="15%">Pengadu</th>
                <th width="20%">Judul Pengaduan</th>
                <th width="15%">Lokasi</th>
                <th width="17%">Ditangani Oleh</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengaduan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->id_pengaduan }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tgl_pengajuan)->format('d/m/Y') }}</td>
                <td>{{ $item->user->nama_pengguna }}</td>
                <td>{{ Str::limit($item->nama_pengaduan, 35) }}</td>
                <td>{{ Str::limit($item->lokasi, 25) }}</td>
                <td>
                    @if($item->petugas)
                        <strong>Petugas:</strong> {{ $item->petugas->nama }}
                        @if($item->petugas->pekerjaan)
                            <br><small>({{ $item->petugas->pekerjaan }})</small>
                        @endif
                    @else
                        <em>Belum ditugaskan</em>
                    @endif
                </td>
                <td>
                    <span class="status-badge status-{{ strtolower($item->status) }}">
                        {{ $item->status }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px; color: #9ca3af;">
                    Tidak ada data pengaduan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Data: <strong>{{ $pengaduan->count() }}</strong> pengaduan</p>
        <p>Dicetak pada: {{ $tanggal_export }}</p>
    </div>
</body>
</html>
