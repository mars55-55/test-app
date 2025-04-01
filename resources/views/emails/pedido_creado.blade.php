<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Pedido</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <h1 style="color: #28a745; text-align: center;">Nuevo Pedido</h1>
        <p style="font-size: 16px; color: #333;">Un nuevo pedido ha sido creado. Aquí están los detalles:</p>
        <ul style="font-size: 16px; color: #555; line-height: 1.6;">
            <li><strong>Producto:</strong> {{ $pedido->producto }}</li>
            <li><strong>Cantidad:</strong> {{ $pedido->cantidad }}</li>
            <li><strong>Total:</strong> ${{ number_format($pedido->total, 2) }}</li>
        </ul>
        <p style="font-size: 14px; color: #777; text-align: center; margin-top: 20px;">Gracias por usar nuestra plataforma.</p>
    </div>
</body>
</html>