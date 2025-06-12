<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Aplicacion;

class AplicacionController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('aplicacion/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
    
        try {
            $_POST['app_nombre_largo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['app_nombre_largo']))));
            
            $cantidad_largo = strlen($_POST['app_nombre_largo']);
            
            if ($cantidad_largo < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre largo debe de tener mas de 1 caracteres'
                ]);
                exit;
            }
            
            if ($cantidad_largo > 250) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre largo no puede exceder los 250 caracteres'
                ]);
                exit;
            }
            
            $_POST['app_nombre_medium'] = ucwords(strtolower(trim(htmlspecialchars($_POST['app_nombre_medium']))));
            
            $cantidad_medium = strlen($_POST['app_nombre_medium']);
            
            if ($cantidad_medium < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre mediano debe de tener mas de 1 caracteres'
                ]);
                exit;
            }
            
            if ($cantidad_medium > 150) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre mediano no puede exceder los 150 caracteres'
                ]);
                exit;
            }
            
            $_POST['app_nombre_corto'] = strtoupper(trim(htmlspecialchars($_POST['app_nombre_corto'])));
            $cantidad_corto = strlen($_POST['app_nombre_corto']);
            
            if ($cantidad_corto < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre corto debe de tener mas de 1 caracteres'
                ]);
                exit;
            }
            
            if ($cantidad_corto > 50) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre corto no puede exceder los 50 caracteres'
                ]);
                exit;
            }
            
            $_POST['app_fecha_creacion'] = '';
            $_POST['app_situacion'] = 1;
            
            $aplicacion = new Aplicacion($_POST);
            $resultado = $aplicacion->crear();

            if($resultado['resultado'] == 1){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Aplicacion registrada correctamente',
                ]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error en registrar la aplicacion',
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
        try {
            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

            $condiciones = ["app_situacion = 1"];

            if ($fecha_inicio) {
                $condiciones[] = "app_fecha_creacion >= '{$fecha_inicio}'";
            }

            if ($fecha_fin) {
                $condiciones[] = "app_fecha_creacion <= '{$fecha_fin}'";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT * FROM aplicacion WHERE $where ORDER BY app_fecha_creacion DESC";
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

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['app_id'];
        $_POST['app_nombre_largo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['app_nombre_largo']))));

        $cantidad_largo = strlen($_POST['app_nombre_largo']);

        if ($cantidad_largo < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre largo debe de tener mas de 1 caracteres'
            ]);
            return;
        }

        if ($cantidad_largo > 250) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre largo no puede exceder los 250 caracteres'
            ]);
            return;
        }

        $_POST['app_nombre_medium'] = ucwords(strtolower(trim(htmlspecialchars($_POST['app_nombre_medium']))));

        $cantidad_medium = strlen($_POST['app_nombre_medium']);

        if ($cantidad_medium < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre mediano debe de tener mas de 1 caracteres'
            ]);
            return;
        }

        if ($cantidad_medium > 150) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre mediano no puede exceder los 150 caracteres'
            ]);
            return;
        }

        $_POST['app_nombre_corto'] = strtoupper(trim(htmlspecialchars($_POST['app_nombre_corto'])));
        $cantidad_corto = strlen($_POST['app_nombre_corto']);

        if ($cantidad_corto < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre corto debe de tener mas de 1 caracteres'
            ]);
            return;
        }

        if ($cantidad_corto > 50) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre corto no puede exceder los 50 caracteres'
            ]);
            return;
        }

        try {
            $data = Aplicacion::find($id);
            $data->sincronizar([
                'app_nombre_largo' => $_POST['app_nombre_largo'],
                'app_nombre_medium' => $_POST['app_nombre_medium'],
                'app_nombre_corto' => $_POST['app_nombre_corto'],
                'app_situacion' => 1
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La informacion de la aplicacion ha sido modificada exitosamente'
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
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            $ejecutar = Aplicacion::EliminarAplicaciones($id);

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
}