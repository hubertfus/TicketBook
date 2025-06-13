<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1,
        h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2e8f0;
        }

        .info-block {
            margin-bottom: 10px;
        }

        .total {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }

        .footer {
            font-size: 10px;
            text-align: center;
            margin-top: 50px;
            color: #888;
        }

        .status-banner {
            padding: 10px;
            background-color: #fce7e7;
            border: 2px solid #ff0000;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    @if ($order->status === 'cancelled' || $order->status === 'refunded')
        <div class="status-banner">
            {{ strtoupper($order->status) }} ORDER
        </div>
    @endif

    <h1>Order Confirmation</h1>

    <div class="info-block">
        <p><strong>Confirmation No.:</strong> TK-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
    </div>

    <div class="info-block">
        <p><strong>Buyer:</strong> {{ $order->user->name }} ({{ $order->user->email }})</p>
    </div>

    @php
        $event = $order->orderItems->first()->ticket->event ?? null;
    @endphp
    @if ($event)
        <div class="info-block">
            <p><strong>Event:</strong> {{ $event->title }}</p>
            <p><strong>Date:</strong> {{ $event->date->format('Y-m-d H:i') }}</p>
            <p><strong>Location:</strong> {{ $event->location }}</p>
        </div>
    @endif

    <h3>Purchased Tickets</h3>
    <table>
        <thead>
            <tr>
                <th>Ticket Category</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderItems as $item)
                <tr>
                    <td>{{ ucfirst($item->ticket->category) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 2, ',', ' ') }} PLN</td>
                    <td>{{ number_format($item->total_price, 2, ',', ' ') }} PLN</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">Total Paid: {{ number_format($order->total_price, 2, ',', ' ') }} PLN</p>

    <p class="footer">
        This is an automatically generated confirmation.
        @if ($order->status === 'refunded')
            This order has been refunded and is no longer valid for entry.
        @elseif ($order->status === 'cancelled')
            This order was cancelled and is invalid.
        @else
            No signature or stamp is required.
        @endif
    </p>
</body>

</html>
