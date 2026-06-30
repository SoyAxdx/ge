<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Error interno del servidor</title>
    <link rel="icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .error-container {
            text-align: center;
            padding: 60px 20px;
        }
        .error-container .codigo {
            font-size: 120px;
            font-weight: bold;
            color: #e74c3c;
            margin: 0;
            line-height: 1;
        }
        .error-container h2 {
            font-size: 28px;
            color: #333;
            margin: 10px 0;
        }
        .error-container p {
            color: #666;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .error-container .btn {
            display: inline-block;
            width: auto;
            padding: 10px 25px;
        }
    </style>
</head>
<body>
    <div class="container error-container">
        <div class="codigo">500</div>
        <h2>⚠️ Error interno del servidor</h2>
        <p>Ha ocurrido un error inesperado. Por favor, intenta de nuevo más tarde.</p>
        <a href="index.php?action=dashboard" class="btn btn-primary">Volver al Dashboard</a>
        <br><br>
        <a href="index.php?action=login" class="btn btn-secondary" style="display:inline-block; width:auto; padding:10px 25px;">Ir al inicio de sesión</a>
    </div>
</body>
</html>