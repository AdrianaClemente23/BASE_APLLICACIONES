<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Clemente</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-dark mb-3">Sistema Clemente</h1>
            <p class="lead text-muted mx-auto" style="max-width: 600px;">
                Administra de manera eficiente nuestra aplicación con módulos para usuarios, aplicaciones y permisos.
            </p>
        </div>
        
        <!-- Módulos -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <span class="fs-1">👥</span>
                        </div>
                        <h5 class="card-title fw-bold">Gestión de Usuarios</h5>
                        <p class="card-text text-muted">Administra tu base de usuarios con información completa y segura.</p>
                        <a href="/clementeperez/usuarios" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <span class="fs-1">📱</span>
                        </div>
                        <h5 class="card-title fw-bold">Control de Aplicaciones</h5>
                        <p class="card-text text-muted">Gestiona las aplicaciones del sistema con nombres y configuraciones.</p>
                        <a href="/clementeperez/aplicacion" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <span class="fs-1">🔐</span>
                        </div>
                        <h5 class="card-title fw-bold">Sistema de Permisos</h5>
                        <p class="card-text text-muted">Controla el acceso y gestiona permisos por aplicación de forma segura.</p>
                        <a href="/clementeperez/permisos" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Características -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-5 text-center">
                <h2 class="h3 fw-bold mb-4">Características Principales</h2>
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <p class="mb-0"><strong class="text-primary">Organiza:</strong> mantén ordenados todos los datos de usuarios y aplicaciones.</p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-0"><strong class="text-primary">Controla:</strong> gestiona permisos y accesos de forma granular y segura.</p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-0"><strong class="text-primary">Administra:</strong> supervisa todo el sistema desde una interfaz intuitiva.</p>
                    </div>
                </div>
                <a href="/clementeperez/usuarios" class="btn btn-primary btn-lg">Comenzar a administrar</a>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>