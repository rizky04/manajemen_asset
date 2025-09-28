<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Aset - {{ $asset->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 p-6">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-4">{{ $asset->name }}</h1>
        <p><strong>Kode:</strong> {{ $asset->asset_code }}</p>
        <p><strong>Perusahaan:</strong> {{ $asset->company?->name }}</p>
        <p><strong>Lokasi:</strong> {{ $asset->location?->name }}</p>
        <p><strong>PIC:</strong> {{ $asset->employee?->name }}</p>
        <p><strong>Status:</strong> {{ ucfirst($asset->status) }}</p>

        <h2 class="text-xl font-semibold mt-6 mb-2">Riwayat Transaksi</h2>
        <table class="w-full border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border">Jenis</th>
                    <th class="p-2 border">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asset->transactions as $trx)
                    <tr>
                        <td class="p-2 border">{{ $trx->date }}</td>
                        <td class="p-2 border">{{ ucfirst($trx->transaction_type) }}</td>
                        <td class="p-2 border">{{ $trx->note }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
