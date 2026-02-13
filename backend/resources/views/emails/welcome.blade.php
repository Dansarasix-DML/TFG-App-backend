<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activación de Cuenta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #007bff;
            padding: 10px;
            border-radius: 8px 8px 0 0;
            text-align: center;
            color: #ffffff;
        }
        .content {
            padding: 20px;
            line-height: 1.6;
            color: #333333;
        }
        .content p {
            margin: 10px 0;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #999999;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Activa tu Cuenta</h1>
        </div>
        <div class="content">
            <p>Hola, {{ $name }},</p>
            <p>Tu usuario es <strong>{{ $username }}</strong>.</p>
            <p>Para activar tu cuenta, haz clic en el siguiente botón:</p>
            <p><a href="https://gameverseproject.tech/activate/{{$token}}" class="button">AQUÍ</a></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Gameverse. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
