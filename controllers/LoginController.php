<?php

namespace Controllers;

use Model\ActiveRecord;
use MVC\Router;
use Exception;

class LoginController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
            header('Location: /clementeperez/inicio');
            exit;
        }
        
        $router->render('login/index', [], 'layout/layoutlogin');
    }

    public static function login() {
        getHeadersApi();
        
        try {
            // Validar que se enviaron los datos
            if (empty($_POST['usu_codigo']) || empty($_POST['usu_password'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'DPI y contraseña son obligatorios'
                ]);
                exit;
            }

            $dpi = htmlspecialchars($_POST['usu_codigo']);
            $contrasena = htmlspecialchars($_POST['usu_password']);

            $queryExisteUser = "SELECT usuario_id, usuario_nom1, usuario_nom2, usuario_ape1, usuario_ape2, usuario_contra, usuario_dpi FROM usuario WHERE usuario_dpi = '$dpi' AND usuario_situacion = 1";

           
            try {
                $existeUsuario = ActiveRecord::fetchFirst($queryExisteUser);
            } catch (Exception $dbError) {
                // Si hay error en la consulta, intentar con fetchArray
                $resultados = ActiveRecord::fetchArray($queryExisteUser);
                $existeUsuario = !empty($resultados) ? $resultados[0] : null;
            }

            if ($existeUsuario) {
                $passDB = $existeUsuario['usuario_contra'];

                if (password_verify($contrasena, $passDB)) {
                    session_start();
                    
                    // Guardar información del usuario en la sesión
                    $nombreCompleto = trim($existeUsuario['usuario_nom1'] . ' ' . $existeUsuario['usuario_nom2'] . ' ' . $existeUsuario['usuario_ape1'] . ' ' . $existeUsuario['usuario_ape2']);
                    
                    $_SESSION['user'] = $nombreCompleto;
                    $_SESSION['user_id'] = $existeUsuario['usuario_id'];
                    $_SESSION['dpi'] = $dpi;
                    $_SESSION['login'] = true;

                    // Buscar permisos del usuario en asig_permisos
                    $sqlPermisos = "SELECT ap.*, a.app_nombre_corto, p.permiso_clave 
                                   FROM asig_permisos ap 
                                   INNER JOIN aplicacion a ON ap.asignacion_app_id = a.app_id 
                                   INNER JOIN permiso p ON ap.asignacion_permiso_id = p.permiso_id 
                                   WHERE ap.asignacion_usuario_id = {$existeUsuario['usuario_id']} 
                                   AND ap.asignacion_situacion = 1 
                                   AND a.app_situacion = 1 
                                   AND p.permiso_situacion = 1";

                    try {
                        $permisos = ActiveRecord::fetchArray($sqlPermisos);
                        
                        // Guardar permisos en la sesión
                        if (!empty($permisos)) {
                            foreach ($permisos as $permiso) {
                                $_SESSION[$permiso['permiso_clave']] = true;
                            }
                            // Guardar rol principal (el primer permiso encontrado)
                            $_SESSION['rol'] = $permisos[0]['permiso_clave'];
                        } else {
                            // Usuario sin permisos específicos
                            $_SESSION['rol'] = 'USER';
                            $_SESSION['USER'] = true;
                        }
                    } catch (Exception $dbError) {
                        // Si hay error en permisos, dar permisos básicos
                        $_SESSION['rol'] = 'USER';
                        $_SESSION['USER'] = true;
                    }

                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Usuario logueado exitosamente',
                    ]);
                } else {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La contraseña que ingresó es incorrecta',
                    ]);
                }
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El DPI ingresado no está registrado en el sistema',
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al intentar loguearse',
                'detalle' => $e->getMessage()
            ]);
        }
        exit;
    }

    public static function logout(){
        isAuth();
        $_SESSION = [];
        $login = $_ENV['APP_NAME'];
        header("Location: /$login");
        exit;
    }
}