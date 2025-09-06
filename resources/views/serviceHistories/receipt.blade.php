<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Service Receipt - {{ $serviceHistory->invoice_number }}</title>
    <style>
        body {
            font-family: monospace;
            font-size: 10px;
            color: #000;
        }

        .container {
            width: 220px;
            /* Common width for 58mm thermal printers */
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 5px;
        }

        .header h1 {
            font-size: 14px;
            margin: 0;
        }

        .content table {
            width: 100%;
            border-collapse: collapse;
        }

        .content th,
        .content td {
            padding: 2px;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            text-align: center;
            margin-top: 5px;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name', 'SelulerKu') }}</h1>
            <hr>
            <p>{{ setting('receipt_address', 'Unset') }}</p>
            <p>{{ $serviceHistory->created_at->format('d/m/Y H:i') }}</p>
            <p>Cashier: {{ $serviceHistory->user->name }}</p>
            <p>Invoice: {{ $serviceHistory->invoice_number }}</p>
            <p>Customer: {{ $serviceHistory->customer->name }}</p>
            <p>Warranty Expired: {{ $serviceHistory->warranty_expired_at->format('d/m/Y') }}</p>
        </div>
        <hr>
        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th class="text-right">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($serviceHistory->details as $detail)
                    <tr>
                        <td colspan="2">{{ $detail->kind }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">{{ $detail->description }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-right">Rp. {{ number_format($detail->price, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-right">Total</th>
                        <th class="text-right">Rp. {{ number_format($serviceHistory->details->sum('price'), 0, ',', '.')
                            }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <hr>
        <div class="footer">
            <p>{{ setting('contact_phone', '0812-3456-7890') }}</p>
            <p>{{ setting('receipt_footer', 'Thank you for your purchase!') }}</p>
        </div>
    </div>
</body>

</html>v>
</div>
</body>

</html>