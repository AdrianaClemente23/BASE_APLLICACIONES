<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\AsignacionPermisos;

class AsignacionPermisosController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('asignacionpermisos/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
    
        try {
            // Validar usuario
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
            $existe = AsignacionPermisos::VerificarPermiso(
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
            $_POST['asignacion_fecha'] = '';
            $_POST['asignacion_situacion'] = 1;
            
            $asignacion = new AsignacionPermisos($_POST);
            $resultado = $asignacion->crear();

            if($resultado['resultado'] == 1){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Permiso asignado correctamente',
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
            $usuario_id = isset($_GET['usuario_id']) ? $_GET['usuario_id'] : null;
            $app_id = isset($_GET['app_id']) ? $_GET['app_id'] : null;

            $condiciones = ["ap.asignacion_situacion = 1"];

            if ($usuario_id) {
                $condiciones[] = "ap.asignacion_usuario_id = {$usuario_id}";
            }

            if ($app_id) {
                $condiciones[] = "ap.asignacion_app_id = {$app_id}";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT 
                        ap.*,
                        u.usuario_nom1,
                        u.usuario_ape1,
                        a.app_nombre_corto,
                        p.permiso_nombre,
                        p.permiso_clave,
                        p.permiso_desc,
                        p.permiso_tipo,
                        ua.usuario_nom1 as asigno_nom1,
                        ua.usuario_ape1 as asigno_ape1
                    FROM asig_permisos ap 
                    INNER JOIN usuario u ON ap.asignacion_usuario_id = u.usuario_id
                    INNER JOIN aplicacion a ON ap.asignacion_app_id = a.app_id 
                    INNER JOIN permiso p ON ap.asignacion_permiso_id = p.permiso_id
                    INNER JOIN usuario ua ON ap.asignacion_usuario_asigno = ua.usuario_id
                    WHERE $where 
                    ORDER BY ap.asignacion_fecha DESC";
            
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asignaciones obtenidas correctamente',
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
        
        $_POST['asignacion_usuario_id'] = filter_var($_POST['asignacion_usuario_id'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['asignacion_app_id'] = filter_var($_POST['asignacion_app_id'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['asignacion_permiso_id'] = filter_var($_POST['asignacion_permiso_id'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['asignacion_usuario_asigno'] = filter_var($_POST['asignacion_usuario_asigno'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['asignacion_motivo'] = trim(htmlspecialchars($_POST['asignacion_motivo'] ?? ''));

        try {
            $data = AsignacionPermisos::find($id);
            $data->sincronizar([
                'asignacion_usuario_id' => $_POST['asignacion_usuario_id'],
                'asignacion_app_id' => $_POST['asignacion_app_id'],
                'asignacion_permiso_id' => $_POST['asignacion_permiso_id'],
                'asignacion_usuario_asigno' => $_POST['asignacion_usuario_asigno'],
                'asignacion_motivo' => $_POST['asignacion_motivo'],
                'asignacion_situacion' => 1
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La información de la asignación ha sido modificada exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function EliminarAPI()
    {
        getHeadersApi();
        
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            $ejecutar = AsignacionPermisos::EliminarAsignacion($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El registro ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al Eliminar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    // APIs auxiliares para los selects
    public static function buscarUsuariosAPI()
    {
        getHeadersApi();
        
        try {
            $sql = "SELECT usuario_id, usuario_nom1, usuario_ape1 
                    FROM usuario 
                    WHERE usuario_situacion = 1 
                    ORDER BY usuario_nom1";
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
            $sql = "SELECT app_id, app_nombre_corto, app_nombre_medium 
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
            $app_id = isset($_GET['app_id']) ? intval($_GET['app_id']) : null;
            
            $condiciones = ["permiso_situacion = 1"];
            
            if ($app_id && $app_id > 0) {
                $condiciones[] = "app_id = {$app_id}";
            }
            
            $where = implode(" AND ", $condiciones);
            $sql = "SELECT permiso_id, permiso_nombre, permiso_clave, permiso_desc, permiso_tipo, app_id
                    FROM permiso 
                    WHERE $where 
                    ORDER BY permiso_nombre";
            
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