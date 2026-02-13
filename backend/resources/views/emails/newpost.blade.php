<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        .notificationCard {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            margin: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .notificationCard-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }
        .notificationCard-body {
            display: flex;
            justify-content: space-between;
        }
        .notificationCard-details p {
            margin: 4px 0;
        }
        .notificationCard-footer {
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
    <p>¡Tenemos buenas noticias! Se ha publicado un nuevo post en el blog al que estás suscrito.</p>
    <div class="notificationCard">
        <div class="notificationCard-header">
            <h2>{{ $postTitle }}</h2>
        </div>
        <div class="notificationCard-body">
            <div class="notificationCard-details">
                <p><strong>Fecha de Publicación:</strong> {{ $postDate }}</p>
                <p><strong>Autor:</strong> {{ $postAuthor }}</p>
                <p>{{ $postExcerpt }}</p>
            </div>
        </div>
        <div class="notificationCard-footer">
            <a href="{{ $postUrl }}">Leer más</a>
        </div>
    </div>
    <p>Gracias por ser parte de nuestra comunidad.</p>
</body>
</html>
