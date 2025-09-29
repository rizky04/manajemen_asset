<!DOCTYPE html>
<html>
<head>
    <title>Print QR Semua Asset</title>
    <style>
        .qr-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .qr-item {
            text-align: center;
            border: 1px solid #ddd;
            padding: 10px;
            width: 150px;
        }
    </style>
</head>
<body onload="window.print()">
    <h2>QR Code Semua Asset</h2>

    <div class="qr-container">
        @foreach($assets as $asset)
            <div class="qr-item">
                {!! QrCode::size(120)->generate(route('assets.public.show', $asset->id)) !!}
                <small>{{ $asset->asset_code }}</small>
                <small>{{ $asset->name }}</small>
            </div>
        @endforeach
    </div>
</body>
</html>
