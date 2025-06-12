<style>
    body {
        background: linear-gradient(135deg,rgb(109, 109, 109) 0%,rgb(179, 188, 204) 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
        background-color: #f8f9fa;
    }

    .header {
        padding: 2rem;
        text-align: center;
        border-radius: 15px;
        margin-top: 2rem;
        margin-bottom: 2rem;
        max-width: 1140px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .logo {
        font-size: 3rem;
        font-weight: bold;
        color: #2d3748;
        margin-bottom: 1rem;
        max-width: 1140px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .container {
        max-width: 1140px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .product-img {
      border-radius: 10px;
      width: 100%;
      height: 100%;
      max-height: 300px;
      object-fit: cover;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      margin-bottom: 1rem;
      background-color: #e9ecef;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #6c757d;
      font-size: 1.2rem;
      text-align: center;
    }

    .product-img:hover {
      transform: scale(1.05) rotate(-2deg);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
      cursor: pointer;
    }
    
</style>
<body>
    <div class="header">
        <div class="logo">¡Bienvenido al Sistema clemente!</div>
    </div>
    
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-8 mx-auto text-center">
                <p class="lead">
                    "Administra de manera eficiente nuestra aplicación con módulos para usuarios, aplicaciones y permisos, controla el acceso, gestiona roles y mantén la seguridad de forma sencilla y profesional."
                </p>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-12 text-center mb-4">
                <h2 class="text-uppercase fw-bold">Módulos del Sistema</h2>
                <p class="text-muted">Gestiona todos los aspectos de administración desde una sola plataforma.</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                        <img src="https://img.freepik.com/vector-premium/usuarios-grupo-personas-icono-perfil-usuario_24877-40756.jpg" alt="Gestión de Usuarios" style="max-width:100%; max-height:100%; border-radius:10px;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Gestión de Usuarios</h5>
                        <p class="card-text text-muted">Administra tu base de usuarios con información completa y segura.</p>
                        <a href="/clementeperez/usuarios" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                      <img src="https://img.freepik.com/vector-gratis/diseno-multimedia-color_1284-883.jpg?semt=ais_items_boosted&w=740" alt="aplicaciones" style="max-width:100%; max-height:100%; border-radius:10px;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Control de Aplicaciones</h5>
                        <p class="card-text text-muted">Gestiona las aplicaciones del sistema con nombres y configuraciones.</p>
                        <a href="/clementeperez/aplicacion" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                      <img src="https://img.freepik.com/vector-gratis/desarrollo-aplicaciones-moviles_24908-58350.jpg?semt=ais_hybrid&w=740" alt="permisos" style="max-width:100%; max-height:100%; border-radius:10px;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Sistema de Permisos</h5>
                        <p class="card-text text-muted">Controla el acceso y gestiona permisos por aplicación de forma segura.</p>
                        <a href="/clementeperez/permisos" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-md-6 mx-auto text-center">
                <h2 class="mb-4 text-uppercase fw-bold">Características Principales</h2><br>
                <p><strong>Organiza:</strong> mantén ordenados todos los datos de usuarios y aplicaciones.</p>
                <p><strong>Controla:</strong> gestiona permisos y accesos de forma granular y segura.</p>
                <p><strong>Administra:</strong> supervisa todo el sistema desde una interfaz intuitiva y moderna.</p>
                <a href="/clementeperez/usuarios" class="btn btn-primary btn-lg mt-3">Comenzar a administrar</a>
            </div>
        </div>
    </div>
    <script src="<?= asset('build/js/inicio.js') ?>"></script>
</body>