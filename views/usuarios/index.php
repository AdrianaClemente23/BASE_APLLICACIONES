<div class="container py-5">
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">¡Bienvenido Nuestra Aplicación!</h5>
                        <h3 class="fw-bold text-primary mb-0">MANIPULACIÓN DE USUARIOS</h3>
                    </div>
                    <form id="formUsuario" class="p-4 bg-white rounded-3 shadow-sm border" enctype="multipart/form-data">
                        <input type="hidden" id="usuario_id" name="usuario_id">
                        <input type="hidden" id="usuario_token" name="usuario_token" value="">
                        <input type="hidden" id="usuario_fecha_creacion" name="usuario_fecha_creacion" value="">
                        <input type="hidden" id="usuario_fecha_contra" name="usuario_fecha_contra" value="">
                        <input type="hidden" id="usuario_situacion" name="usuario_situacion" value="1">
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="usuario_nom1" class="form-label">Primer Nombre</label>
                                <input type="text" class="form-control form-control-lg" id="usuario_nom1" name="usuario_nom1" placeholder="Ingrese primer nombre" required>
                            </div>
                            <div class="col-md-6">
                                <label for="usuario_nom2" class="form-label">Segundo Nombre</label>
                                <input type="text" class="form-control form-control-lg" id="usuario_nom2" name="usuario_nom2" placeholder="Ingrese segundo nombre">
                            </div>
                        </div>
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="usuario_ape1" class="form-label">Primer Apellido</label>
                                <input type="text" class="form-control form-control-lg" id="usuario_ape1" name="usuario_ape1" placeholder="Ingrese primer apellido" required>
                            </div>
                            <div class="col-md-6">
                                <label for="usuario_ape2" class="form-label">Segundo Apellido</label>
                                <input type="text" class="form-control form-control-lg" id="usuario_ape2" name="usuario_ape2" placeholder="Ingrese segundo apellido">
                            </div>
                        </div>
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="usuario_tel" class="form-label">Teléfono</label>
                                <input type="text" class="form-control form-control-lg" id="usuario_tel" name="usuario_tel" placeholder="Ingrese número de teléfono" required>
                            </div>
                            <div class="col-md-6">
                                <label for="usuario_dpi" class="form-label">DPI</label>
                                <input type="text" class="form-control form-control-lg" id="usuario_dpi" name="usuario_dpi" placeholder="Ingrese DPI" required>
                            </div>
                        </div>
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="usuario_direc" class="form-label">Dirección</label>
                                <input type="text" class="form-control form-control-lg" id="usuario_direc" name="usuario_direc" placeholder="Ingrese dirección" required>
                            </div>
                            <div class="col-md-6">
                                <label for="usuario_correo" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control form-control-lg" id="usuario_correo" name="usuario_correo" placeholder="ejemplo@ejemplo.com" required>
                            </div>
                        </div>
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="usuario_contra" class="form-label">Contraseña</label>
                                <input type="password" class="form-control form-control-lg" id="usuario_contra" name="usuario_contra" placeholder="Ingrese contraseña" required>
                            </div>
                            <div class="col-md-6">
                                <label for="confirmar_contra" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control form-control-lg" id="confirmar_contra" name="confirmar_contra" placeholder="Confirme contraseña" required>
                            </div>
                        </div>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <label for="usuario_fotografia" class="form-label">Fotografía</label>
                                <input type="file" class="form-control form-control-lg" id="usuario_fotografia" name="usuario_fotografia" accept=".jpg,.jpeg,.png">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-success btn-lg px-4 shadow" type="submit" id="BtnGuardar">
                                <i class="bi bi-save me-2"></i>Guardar
                            </button>
                            <button class="btn btn-warning btn-lg px-4 shadow d-none" type="button" id="BtnModificar">
                                <i class="bi bi-pencil-square me-2"></i>Modificar
                            </button>
                            <button class="btn btn-secondary btn-lg px-4 shadow" type="reset" id="BtnLimpiar">
                                <i class="bi bi-eraser me-2"></i>Limpiar
                            </button>
                            <button class="btn btn-primary btn-lg px-4 shadow" type="button" id="BtnBuscarUsuarios">
                                <i class="bi bi-search me-2"></i>Buscar Usuarios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-5" id="seccionTabla" style="display: none;">
        <div class="col-lg-11">
            <div class="card shadow-lg border-primary rounded-4">
                <div class="card-body">
                    <h3 class="text-center text-primary mb-4">Usuarios registrados en la base de datos</h3>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden w-100" id="TableUsuarios" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Primer Nombre</th>
                                    <th>Segundo Nombre</th>
                                    <th>Primer Apellido</th>
                                    <th>Segundo Apellido</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>DPI</th>
                                    <th>Dirección</th>
                                    <th>Fotografía</th>
                                    <th>Situación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="<?= asset('build/js/usuarios/index.js') ?>"></script>