<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        .orderCard {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            margin: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .orderCard-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }
        .orderCard-body {
            display: flex;
            justify-content: space-between;
        }
        .orderCard-details p {
            margin: 4px 0;
        }
        .orderCard-status {
            padding: 8px;
            border-radius: 4px;
            color: #fff;
            text-align: center;
        }
        .orderCard-status.pending {
            background-color: #f0ad4e;
        }
        .orderCard-status.completed {
            background-color: #5cb85c;
        }
        .orderCard-status.cancelled {
            background-color: #d9534f;
        }
        .orderCard-footer {
            border-top: 1px solid #eee;
            padding-top: 8px;
            margin-top: 16px;
            text-align: right;
            color: #888;
        }
    </style>
</head>
<body>
    <h1>Hola, {{ $name }},</h1>
    <p>¡Gracias por realizar una nueva compra en GameVerse!</p>
    <div class="orderCard">
        <div class="orderCard-header">
            <h2>Pedido #{{ $id }}</h2>
        </div>
        <div class="orderCard-body">
            <div class="orderCard-details">
                <p><strong>Fecha de Compra:</strong> {{ now()->toDateTimeString() }}</p>
                <p><strong>Subtotal:</strong> {{ $price }}€</p>
                <p><strong>IVA:</strong> {{ $tax }}€</p>
                <p className="orderCard-details-total"><strong>Total:</strong> {{ $total }}€</p>
            </div>
            <div class="orderCard-status {{ strtolower($status) }}">
                <p>{{ $status }}</p>
            </div>
        </div>
        <div class="orderCard-footer">
            <small>Última actualización: {{ now()->toDateTimeString() }}</small>
        </div>
    </div>
</body>
</html>
