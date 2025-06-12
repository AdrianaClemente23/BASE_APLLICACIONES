<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="build/js/app.js"></script>
    <link rel="shortcut icon" href="<?= asset('images/cit.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title>Sistema de Gestión de Permisos</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        
        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="/clementeperez/inicio">
                <img src="<?= asset('./images/cit.png') ?>" width="35px'" alt="cit" >
                Sistema de Permisos
            </a>
            <div class="collapse navbar-collapse" id="navbarToggler">
                
                <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin: 0;">
                    <!-- MENÚ PRINCIPAL -->
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/clementeperez/inicio">
                            <i class="bi bi-house-fill me-2"></i>Inicio
                        </a>
                    </li>

                    <!-- GESTIÓN DE USUARIOS -->
                    <li class="nav-item">
                        <a class="nav-link px-3" style="border: none; background: none;" href="/clementeperez/usuarios">
                            <i class="bi bi-people-fill me-2"></i>Usuarios
                        </a>
                    </li>

                    <!-- GESTIÓN DE APLICACIONES -->
                    <li class="nav-item">
                        <a class="nav-link px-3" style="background: none;" href="/clementeperez/aplicacion">
                            <i class="bi bi-grid-fill me-2"></i>Aplicaciones
                        </a>
                    </li>

                    <!-- GESTIÓN DE PERMISOS -->
                    <li class="nav-item">
                        <a class="nav-link px-3" style="background: none; border: none;" href="/clementeperez/permisos">
                            <i class="bi bi-shield-lock-fill me-2"></i>Permisos
                        </a>
                    </li>

                    <!-- ASIGNACIÓN DE PERMISOS (NUEVO) -->
                    <li class="nav-item">
                        <a class="nav-link px-3" style="border: none; background: none;" href="/clementeperez/asignacionpermisos">
                            <i class="bi bi-person-check-fill me-2"></i>Asignar Permisos
                        </a>
                    </li>

                    <!-- MENÚ DROPDOWN ADICIONAL -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-gear me-2"></i>Configuración
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" id="dropwdownRevision" style="margin: 0;">
                            <li>
                                <a class="dropdown-item nav-link text-white" href="/clementeperez/reportes">
                                    <i class="ms-lg-0 ms-2 bi bi-file-earmark-text me-2"></i>Reportes
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link text-white" href="/clementeperez/auditoria">
                                    <i class="ms-lg-0 ms-2 bi bi-clock-history me-2"></i>Auditoría
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item nav-link text-white" href="/clementeperez/configuracion">
                                    <i class="ms-lg-0 ms-2 bi bi-wrench me-2"></i>Configuración
                                </a>
                            </li>
                        </ul>
                    </div> 

                    <!-- LOGIN/LOGOUT -->
                    <li class="nav-item">
                        <a class="nav-link px-3" style="border: none; background: none;" href="/clementeperez/login">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </a>
                    </li>

                </ul> 
                
                <!-- BOTÓN DE REGRESO AL MENÚ -->
                <div class="col-lg-1 d-grid mb-lg-0 mb-2">
                    <a href="/clementeperez/inicio" class="btn btn-danger">
                        <i class="bi bi-arrow-bar-left me-1"></i>MENÚ
                    </a>
                </div>

            </div>
        </div>
        
    </nav>

    <!-- BARRA DE PROGRESO -->
    <div class="progress fixed-bottom" style="height: 6px;">
        <div class="progress-bar progress-bar-animated bg-danger" id="bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="container-fluid pt-5 mb-4" style="min-height: 85vh">
        <?php echo $contenido; ?>
    </div>

    <!-- FOOTER -->
    <div class="container-fluid">
        <div class="row justify-content-center text-center">
            <div class="col-12">
                <p style="font-size:xx-small; font-weight: bold;">
                    Comando de Informática y Tecnología, <?= date('Y') ?> &copy;
                    <span class="text-muted">| Sistema de Gestión de Permisos y Aplicaciones</span>
                </p>
            </div>
        </div>
    </div>
</body>
</html>