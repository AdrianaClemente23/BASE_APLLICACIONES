<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\AsigPermisos;

class AsigPermisosController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('AsigPermisos/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
    
        try {
            // Validar usuario al que se le asigna
            $_POST['asignacion_usuario_id'] = filter_var($_POST['asignacion_usuario_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($_POST['asignacion_usuario_id'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un usuario válido'
                ]);
                exit;
            }

            // Validar aplicación
            $_POST['asignacion_app_id'] = filter_var($_POST['asignacion_app_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($_POST['asignacion_app_id'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar una aplicación válida'
                ]);
                exit;
            }

            // Validar permiso
            $_POST['asignacion_permiso_id'] = filter_var($_POST['asignacion_permiso_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($_POST['asignacion_permiso_id'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un permiso válido'
                ]);
                exit;
            }

            // Validar usuario que asigna
            $_POST['asignacion_usuario_asigno'] = filter_var($_POST['asignacion_usuario_asigno'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($_POST['asignacion_usuario_asigno'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe especificar quién asigna el permiso'
                ]);
                exit;
            }

            // Verificar que no exista ya la asignación
            $existe = AsigPermisos::VerificarAsignacion(
                $_POST['asignacion_usuario_id'], 
                $_POST['asignacion_app_id'], 
                $_POST['asignacion_permiso_id']
            );

            if ($existe) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este permiso ya está asignado al usuario para esta aplicación'
                ]);
                exit;
            }

            // Preparar datos
            $_POST['asignacion_motivo'] = trim(htmlspecialchars($_POST['asignacion_motivo'] ?? ''));
            $_POST['asignacion_fecha_asignar'] = '';
            $_POST['asignacion_fecha_quitar'] = '';
            $_POST['asignacion_situacion'] = 1;
            
            $asignacion = new AsigPermisos($_POST);
            $resultado = $asignacion->crear();

            if($resultado['resultado'] == 1){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Permiso asignado correctamente al usuario',
                ]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al asignar el permiso',
                ]);
                exit;
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error interno del servidor',
                'detalle' => $e->getMessage(),
            ]);
            exit;
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        
        try {
            $usuario_id = isset($_GET['usuario_id']) ? filter_var($_GET['usuario_id'], FILTER_SANITIZE_NUMBER_INT) : null;
            $app_id = isset($_GET['app_id']) ? filter_var($_GET['app_id'], FILTER_SANITIZE_NUMBER_INT) : null;
            $fecha_inicio = isset($_GET['fecha_inicio']) ? trim($_GET['fecha_inicio']) : null;
            $fecha_fin = isset($_GET['fecha_fin']) ? trim($_GET['fecha_fin']) : null;

            // Construir condiciones para la consulta
            $condiciones = ["ap.asignacion_situacion = 1"];

            if ($usuario_id && $usuario_id > 0) {
                $condiciones[] = "ap.asignacion_usuario_id = {$usuario_id}";
            }

            if ($app_id && $app_id > 0) {
                $condiciones[] = "ap.asignacion_app_id = {$app_id}";
            }

            if ($fecha_inicio) {
                $condiciones[] = "ap.asignacion_fecha_asignar >= '{$fecha_inicio}'";
            }

            if ($fecha_fin) {
                $condiciones[] = "ap.asignacion_fecha_asignar <= '{$fecha_fin}'";
            }

            $where = implode(" AND ", $condiciones);
            
            $sql = "SELECT 
                        ap.*,
                        u.usuario_nom1, u.usuario_ape1, u.usuario_dpi,
                        a.app_nombre_largo, a.app_nombre_medium, a.app_nombre_corto,
                        p.permiso_nombre, p.permiso_clave, p.permiso_desc, p.permiso_tipo,
                        ua.usuario_nom1 as asigno_nom1, ua.usuario_ape1 as asigno_ape1
                    FROM asig_permisos ap 
                    INNER JOIN usuario u ON ap.asignacion_usuario_id = u.usuario_id
                    INNER JOIN aplicacion a ON ap.asignacion_app_id = a.app_id 
                    INNER JOIN permiso p ON ap.asignacion_permiso_id = p.permiso_id
                    INNER JOIN usuario ua ON ap.asignacion_usuario_asigno = ua.usuario_id
                    WHERE $where 
                    ORDER BY ap.asignacion_fecha_asignar DESC";
            
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asignaciones de permisos obtenidas correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las asignaciones',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['asignacion_id'];
        
        // Validar datos
        $_POST['asignacion_usuario_id'] = filter_var($_POST['asignacion_usuario_id'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['asignacion_app_id'] = filter_var($_POST['asignacion_app_id'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['asignacion_permiso_id'] = filter_var($_POST['asignacion_permiso_id'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['asignacion_usuario_asigno'] = filter_var($_POST['asignacion_usuario_asigno'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['asignacion_motivo'] = trim(htmlspecialchars($_POST['asignacion_motivo'] ?? ''));

        try {
            $data = AsigPermisos::find($id);
            
            if (!$data) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Asignación no encontrada'
                ]);
                exit;
            }

            $data->sincronizar([
                'asignacion_usuario_id' => $_POST['asignacion_usuario_id'],
                'asignacion_app_id' => $_POST['asignacion_app_id'],
                'asignacion_permiso_id' => $_POST['asignacion_permiso_id'],
                'asignacion_usuario_asigno' => $_POST['asignacion_usuario_asigno'],
                'asignacion_motivo' => $_POST['asignacion_motivo'],
                'asignacion_situacion' => 1
            ]);
            
            $resultado = $data->actualizar();

            if($resultado['resultado'] >= 0){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'La asignación de permiso ha sido modificada exitosamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al actualizar la asignación'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar los cambios',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function EliminarAPI()
    {
        getHeadersApi();
        
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($id <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de asignación inválido'
                ]);
                exit;
            }

            $ejecutar = AsigPermisos::EliminarAsignacion($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La asignación de permiso ha sido eliminada correctamente'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar la asignación',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function quitarAPI()
    {
        getHeadersApi();
        
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($id <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de asignación inválido'
                ]);
                exit;
            }

            $ejecutar = AsigPermisos::QuitarAsignacion($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El permiso ha sido quitado del usuario correctamente'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al quitar el permiso',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    // APIs auxiliares para llenar los selects
    public static function buscarUsuariosAPI()
    {
        getHeadersApi();
        
        try {
            $sql = "SELECT usuario_id, usuario_nom1, usuario_nom2, usuario_ape1, usuario_ape2, usuario_dpi 
                    FROM usuario 
                    WHERE usuario_situacion = 1 
                    ORDER BY usuario_nom1, usuario_ape1";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios obtenidos correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los usuarios',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarAplicacionesAPI()
    {
        getHeadersApi();
        
        try {
            $sql = "SELECT app_id, app_nombre_largo, app_nombre_medium, app_nombre_corto 
                    FROM aplicacion 
                    WHERE app_situacion = 1 
                    ORDER BY app_nombre_corto";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Aplicaciones obtenidas correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las aplicaciones',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarPermisosAPI()
    {
        getHeadersApi();
        
        try {
            $app_id = isset($_GET['app_id']) ? filter_var($_GET['app_id'], FILTER_SANITIZE_NUMBER_INT) : null;
            $usuario_id = isset($_GET['usuario_id']) ? filter_var($_GET['usuario_id'], FILTER_SANITIZE_NUMBER_INT) : null;
            
            $condiciones = ["p.permiso_situacion = 1"];
            
            if ($app_id && $app_id > 0) {
                $condiciones[] = "p.app_id = {$app_id}";
            }

            if ($usuario_id && $usuario_id > 0) {
                $condiciones[] = "p.usuario_id = {$usuario_id}";
            }
            
            $where = implode(" AND ", $condiciones);
            
            $sql = "SELECT p.permiso_id, p.permiso_nombre, p.permiso_clave, p.permiso_desc, p.permiso_tipo, 
                           p.app_id, p.usuario_id,
                           a.app_nombre_corto,
                           u.usuario_nom1, u.usuario_ape1
                    FROM permiso p
                    INNER JOIN aplicacion a ON p.app_id = a.app_id
                    INNER JOIN usuario u ON p.usuario_id = u.usuario_id
                    WHERE $where 
                    ORDER BY p.permiso_nombre";
            
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permisos obtenidos correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los permisos',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}