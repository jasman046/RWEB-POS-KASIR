<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Receipt</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #222;
            margin: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            font-size: 30px;
        }

        .header p {
            margin: 4px 0;
            color: #555;
        }

        hr {
            border: 0;
            border-top: 1px dashed #999;
            margin: 15px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding: 8px 0;
        }

        td {
            padding: 8px 0;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            margin-top: 20px;
        }

        .summary td {
            padding: 4px 0;
        }

        .total {
            border-top: 2px solid #000;
            font-weight: bold;
            font-size: 15px;
        }

        .barcode {
            margin-top: 35px;
            text-align: center;
        }

        .order-number {
            margin-top: 10px;
            font-weight: bold;
            letter-spacing: 2px;
            font-size: 13px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>

</head>

<body>

<div class="header">
    <h1>ASgor</h1>
    <p>Nasi Goreng</p>
    <p>Receipt</p>
</div>

<hr>

<table>

    <thead>
        <tr>
            <th>Description</th>
            <th class="text-right">Price</th>
        </tr>
    </thead>

    <tbody>

    @foreach($order->orderItems as $item)

        <tr>
            <td>
                {{ $item->quantity }} x {{ $item->product->name }}
            </td>

            <td class="text-right">
                Rp {{ number_format($item->price * $item->quantity,0,',','.') }}
            </td>
        </tr>

    @endforeach

    </tbody>

</table>

<hr>

<table class="summary">

    <tr>
        <td>Payment</td>
        <td class="text-right">{{ $order->payment_method }}</td>
    </tr>

    <tr>
        <td>Order Type</td>
        <td class="text-right">{{ $order->type }}</td>
    </tr>

    <tr>
        <td>Customer</td>
        <td class="text-right">
            {{ $order->customer_name }}
        </td>
    </tr>

    <tr>
        <td>Date</td>
        <td class="text-right">
            {{ $order->created_at->format('d M Y H:i') }}
        </td>
    </tr>

</table>

<hr>

<table>

    <tr class="total">
        <td>Total</td>

        <td class="text-right">
            Rp {{ number_format($order->total_price,0,',','.') }}
        </td>
    </tr>

</table>

<div class="barcode">

    <!-- Barcode dekoratif (SVG statis) -->
    <svg width="220" height="60" xmlns="http://www.w3.org/2000/svg">
        <rect x="0" y="0" width="4" height="60" fill="black"/>
        <rect x="8" y="0" width="8" height="60" fill="black"/>
        <rect x="20" y="0" width="4" height="60" fill="black"/>
        <rect x="28" y="0" width="12" height="60" fill="black"/>
        <rect x="44" y="0" width="4" height="60" fill="black"/>
        <rect x="52" y="0" width="8" height="60" fill="black"/>
        <rect x="64" y="0" width="12" height="60" fill="black"/>
        <rect x="80" y="0" width="4" height="60" fill="black"/>
        <rect x="88" y="0" width="8" height="60" fill="black"/>
        <rect x="100" y="0" width="16" height="60" fill="black"/>
        <rect x="120" y="0" width="4" height="60" fill="black"/>
        <rect x="128" y="0" width="8" height="60" fill="black"/>
        <rect x="140" y="0" width="12" height="60" fill="black"/>
        <rect x="156" y="0" width="4" height="60" fill="black"/>
        <rect x="164" y="0" width="8" height="60" fill="black"/>
    </svg>

    <div class="order-number">
        {{ $order->order_number }}
    </div>

</div>

<div class="footer">
    Thank you for your purchase
</div>

</body>
</html>