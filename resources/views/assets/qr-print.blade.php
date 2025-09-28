<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>QR Aset</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .grid { display: flex; flex-wrap: wrap; }
        .qr-card {
            width: 30%;
            border: 1px solid #ccc;
            margin: 5px;
            padding: 8px;
            text-align: center;
        }
        .qr-card img {
            width: 120px;
            height: 120px;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center; margin-bottom: 20px;">QR Code Aset</h2>
    <div class="grid">
        @foreach($assets as $asset)
            <div class="qr-card">
                <div>
                    {!! QrCode::format('svg')
                        ->size(120)
                        ->generate(route('assets.public.show', $asset->id)) !!}
                </div>
                <p><strong>{{ $asset->asset_code }}</strong></p>
                <p>{{ $asset->name }}</p>
            </div>
        @endforeach
    </div>
</body>
</html>
