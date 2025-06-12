<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Permisos</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: white;
        }

        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 4rem 3rem;
            border-radius: 16px;
            backdrop-filter: blur(10px);
        }

        h1 {
            font-size: 3rem;
            font-weight: 300;
            margin-bottom: 1rem;
        }

        p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2.5rem;
        }

        .btn {
            background: white;
            color: #4f46e5;
            padding: 1rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin: 0 0.5rem;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido</h1>
        <p>Sistema de Gestión de Permisos</p>
        
        <a href="/clementeperez/usuarios" class="btn">Comenzar</a>
        <a href="/clementeperez/login" class="btn btn-outline">Login</a>
    </div>

    <script src="<?= asset('build/js/inicio.js') ?>"></script>
</body>
</html>