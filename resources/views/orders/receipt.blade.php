<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Receipt - {{ $order->invoice_number }}</title>
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
            <p>{{ setting('receipt_address', 'unset') }}</p>
            <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p>Cashier: {{ $order->user->name }}</p>
            <p>Invoice: {{ $order->invoice_number }}</p>
        </div>
        <hr>
        <div class="content">
            <table>
                <tbody>
                    @foreach ($order->details as $detail)
                    <tr>
                        <td colspan="3">{{ $detail->product->name }}</td>
                    </tr>
                    <tr>
                        <td>{{ $detail->quantity }}x</td>
                        <td class="text-right">Rp. {{ number_format($detail->immutable_price / $detail->quantity, 0,
                            ',', '.') }}</td>
                        <td class="text-right">Rp. {{ number_format($detail->immutable_price, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-right">Total</th>
                        <th class="text-right">Rp. {{ number_format($order->details->sum('immutable_price'), 0, ',',
                            '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <hr>
        <div class="footer">
            <p>{{ setting('receipt_footer', 'Thank you for your purchase!') }}</p>
        </div>
    </div>
</body>

</html>>