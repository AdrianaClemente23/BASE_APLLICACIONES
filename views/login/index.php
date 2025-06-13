<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background: #6a11cb;
            background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));
            background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));
        }
        body {
            min-height: 100vh;
        }
        .bg-image {
            background-image: url('https://mdbootstrap.com/img/new/textures/full/171.jpg');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body>

    <section class="text-center">
        <div class="p-5 bg-image" style="height: 300px;"></div>
        <div class="card mx-4 mx-md-5 shadow-5-strong bg-body-tertiary" style="
            margin-top: -100px;
            backdrop-filter: blur(30px);
        ">
            <div class="card-body py-5 px-md-5">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-6">
                        <h2 class="fw-bold mb-5 text-uppercase">INICIO DE SESION</h2>
                        <p class="text-muted mb-5">INGRESA USUARIO Y CONTRASEÑA</p>
                        
                        <form id="FormLogin">
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="text" name="usu_codigo" id="usu_codigo" class="form-control form-control-lg" />
                                <label class="form-label" for="usu_codigo">DPI</label>
                            </div>
                        
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="password" name="usu_password" id="usu_password" class="form-control form-control-lg" />
                                <label class="form-label" for="usu_password">Password</label>
                            </div>
                         

                            <button type="submit" id="BtnIniciar" class="btn btn-primary btn-lg px-5 mb-4">
                                INICIAR SESION
                            </button>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('build/js/login/login.js') ?>"></script>
   
</body>
</html>