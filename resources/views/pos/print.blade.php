<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $order->transaction_code }}</title>
    <style>
        /* Desain ala Printer Thermal 58mm */
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #eee;
            padding: 20px;
        }
        .receipt {
            max-width: 300px; /* Lebar kertas thermal */
            margin: 0 auto;
            background: white;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .line { border-bottom: 1px dashed #000; margin: 10px 0; }
        .flex { display: flex; justify-content: space-between; }
        .text-sm { font-size: 12px; }
        .mt-2 { margin-top: 10px; }
        
        /* Hilangkan elemen browser saat diprint */
        @media print {
            body { background: white; padding: 0; }
            .receipt { box-shadow: none; width: 100%; max-width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="receipt">
        <div class="text-center">
            <h2 style="margin:0;">KEDAI KOPI</h2>
            <p class="text-sm">Jalan Kenangan No. 1<br>Jakarta Selatan</p>
        </div>

        <div class="line"></div>

        <div class="text-sm">
            <div>No: {{ $order->transaction_code }}</div>
            <div>Tgl: {{ $order->created_at->format('d/m/Y H:i') }}</div>
            <div>Kasir: Budi</div>
        </div>

        <div class="line"></div>

        <div class="text-sm">
            @foreach($order->items as $item)
            <div style="margin-bottom: 5px;">
                <div class="bold">{{ $item->product_name }}</div>
                <div class="flex">
                    <span>{{ $item->quantity }} x {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                    <span>{{ number_format($item->sub_total, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="line"></div>

        <div class="flex bold">
            <span>TOTAL</span>
            <span>Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
        <div class="flex text-sm">
            <span>Tunai</span>
            <span>Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
        <div class="flex text-sm">
            <span>Kembali</span>
            <span>Rp0</span>
        </div>

        <div class="line"></div>

        <div class="text-center text-sm">
            <p>Terima Kasih<br>Silakan Datang Kembali</p>
            <p>Wifi: KopiEnak / 12345678</p>
        </div>

        <div class="mt-2 text-center no-print">
            <button onclick="window.print()" style="padding: 10px 20px; background: #000; color: white; border: none; cursor: pointer;">🖨️ Cetak</button>
            <br><br>
            <a href="{{ route('pos.index') }}" style="color: blue; text-decoration: none;">&larr; Transaksi Baru</a>
        </div>
    </div>

</body>
</html>
