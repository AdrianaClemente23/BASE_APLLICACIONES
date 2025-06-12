<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Gestión de Permisos</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            min-height: 100vh;
            margin: 0;
            color: white;
        }

        .header {
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .main-content {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 100px);
            padding: 2rem;
        }

        .welcome-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 4rem 3rem;
            border-radius: 16px;
            backdrop-filter: blur(10px);
            max-width: 600px;
        }

        .welcome-title {
            font-size: 3rem;
            font-weight: 300;
            margin-bottom: 1rem;
            opacity: 0;
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .welcome-message {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            opacity: 0;
            animation: fadeInUp 0.8s ease-out 0.3s forwards;
        }

        .user-greeting {
            font-size: 1.4rem;
            font-weight: 500;
            margin-bottom: 3rem;
            opacity: 0;
            animation: fadeInUp 0.8s ease-out 0.6s forwards;
        }

        .quick-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            opacity: 0;
            animation: fadeInUp 0.8s ease-out 0.9s forwards;
        }

        .action-btn {
            background: white;
            color: #4f46e5;
            padding: 1rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .time-display {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-top: 2rem;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2rem;
            }
            
            .quick-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .action-btn {
                width: 100%;
                max-width: 250px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            Sistema de Gestión
        </div>
        <div class="user-info">
            <div class="user-avatar">U</div>
            <span>Usuario</span>
            <button class="logout-btn" onclick="logout()">Cerrar Sesión</button>
        </div>
    </header>

    <main class="main-content">
        <div class="welcome-container">
            <h1 class="welcome-title">Bienvenido</h1>
            <p class="welcome-message">Sistema de Gestión de Permisos</p>
            <div class="user-greeting">
                ¡Hola! Esperamos que tengas un excelente día.
            </div>
            
            <div class="quick-actions">
                <a href="/dashboard" class="action-btn">
                    📊 Dashboard
                </a>
                <a href="/usuarios" class="action-btn">
                    👥 Usuarios
                </a>
                <a href="/permisos" class="action-btn">
                    🔐 Permisos
                </a>
            </div>
            
            <div class="time-display" id="currentTime"></div>
        </div>
    </main>

    <script>
        // Mostrar hora actual
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('currentTime').textContent = timeString;
        }

        // Función de logout
        function logout() {
            if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
                window.location.href = '/login';
            }
        }

        // Actualizar tiempo cada minuto
        updateTime();
        setInterval(updateTime, 60000);
    </script>
</body>
</html>