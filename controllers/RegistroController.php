<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Usuarios;

class RegistroController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('usuarios/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
    
        try {
            $_POST['usuario_nom1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom1']))));
            
            $cantidad_nombre = strlen($_POST['usuario_nom1']);
            
            if ($cantidad_nombre < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre debe de tener mas de 1 caracteres'
                ]);
                exit;
            }
            
            $_POST['usuario_nom2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom2']))));
            
            $cantidad_nombre = strlen($_POST['usuario_nom2']);
            
            if ($cantidad_nombre < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre debe de tener mas de 1 caracteres'
                ]);
                exit;
            }
            
            $_POST['usuario_ape1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape1']))));
            $cantidad_apellido = strlen($_POST['usuario_ape1']);
            
            if ($cantidad_apellido < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre debe de tener mas de 1 caracteres'
                ]);
                exit;
            }
            
            $_POST['usuario_ape2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape2']))));
            $cantidad_apellido = strlen($_POST['usuario_ape2']);
            
            if ($cantidad_apellido < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre debe de tener mas de 1 caracteres'
                ]);
                exit;
            }
            
            $_POST['usuario_tel'] = filter_var($_POST['usuario_tel'], FILTER_SANITIZE_NUMBER_INT);
            if (strlen($_POST['usuario_tel']) != 8) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El telefono debe de tener 8 numeros'
                ]);
                exit;
            }
            
            $_POST['usuario_direc'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_direc']))));
            
            $_POST['usuario_dpi'] = trim(htmlspecialchars($_POST['usuario_dpi']));
            if (strlen($_POST['usuario_dpi']) != 13) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La cantidad de digitos del DPI debe de ser igual a 13'
                ]);
                exit;
            }
            
            $_POST['usuario_correo'] = filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL);
            
            if (!filter_var($_POST['usuario_correo'], FILTER_VALIDATE_EMAIL)){
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El correo electronico no es valido'
                ]);
                exit;
            }
            
            if (strlen($_POST['usuario_contra']) < 8) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La contraseña debe tener al menos 8 caracteres'
                ]);
                exit;
            }
            
            if ($_POST['usuario_contra'] !== $_POST['confirmar_contra']) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Las contraseñas no coinciden'
                ]);
                exit;
            }
            
            $_POST['usuario_token'] = uniqid();
            $dpi = $_POST['usuario_dpi'];
            $_POST['usuario_fecha_creacion'] = '';
            $_POST['usuario_fecha_contra'] = '';
            
            if (isset($_FILES['usuario_fotografia']) && $_FILES['usuario_fotografia']['error'] !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES['usuario_fotografia'];
                $fileName = $file['name'];
                $fileTmpName = $file['tmp_name'];
                $fileSize = $file['size'];
                $fileError = $file['error'];
                
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
          
                $allowed = ['jpg', 'jpeg', 'png'];
                
                if (!in_array($fileExtension, $allowed)) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 2,
                        'mensaje' => 'Solo puede cargar archivos JPG, PNG o JPEG',
                    ]);
                    exit;
                }
                
                if ($fileSize >= 2000000) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 2,
                        'mensaje' => 'La imagen debe pesar menos de 2MB',
                    ]);
                    exit;
                }
                
                if ($fileError === 0) {
                    $ruta = "storage/fotosUsuarios/$dpi.$fileExtension";
                    
                    $directorioFotos = __DIR__ . "/../../storage/fotosUsuarios/";
                    if (!file_exists($directorioFotos)) {
                        mkdir($directorioFotos, 0755, true);
                    }
                    
                    $subido = move_uploaded_file($file['tmp_name'], __DIR__ . "/../../" . $ruta);
                    
                    if ($subido) {
                        $_POST['usuario_fotografia'] = $ruta;
                    } else {
                        http_response_code(500);
                        echo json_encode([
                            'codigo' => 0,
                            'mensaje' => 'Error al subir la fotografia',
                        ]);
                        exit;
                    }
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Error en la carga de fotografia',
                    ]);
                    exit;
                }
            } else {
                $_POST['usuario_fotografia'] = '';
            }
            
            $_POST['usuario_contra'] = password_hash($_POST['usuario_contra'], PASSWORD_DEFAULT);
            $usuario = new Usuarios($_POST);
            $resultado = $usuario->crear();

            if($resultado['resultado'] == 1){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario registrado correctamente',
                ]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error en registrar al usuario',
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

            $condiciones = ["usuario_situacion = 1"];

            if ($fecha_inicio) {
                $condiciones[] = "usuario_fecha_creacion >= '{$fecha_inicio}'";
            }

            if ($fecha_fin) {
                $condiciones[] = "usuario_fecha_creacion <= '{$fecha_fin}'";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT * FROM usuario WHERE $where ORDER BY usuario_fecha_creacion DESC";
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

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['usuario_id'];
        $_POST['usuario_nom1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom1']))));

        $cantidad_nombre = strlen($_POST['usuario_nom1']);

        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre debe de tener mas de 1 caracteres'
            ]);
            return;
        }

        $_POST['usuario_nom2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom2']))));

        $cantidad_nombre = strlen($_POST['usuario_nom2']);

        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre debe de tener mas de 1 caracteres'
            ]);
            return;
        }

        $_POST['usuario_ape1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape1']))));
        $cantidad_apellido = strlen($_POST['usuario_ape1']);

        if ($cantidad_apellido < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre debe de tener mas de 1 caracteres'
            ]);
            return;
        }

        $_POST['usuario_ape2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape2']))));
        $cantidad_apellido = strlen($_POST['usuario_ape2']);

        if ($cantidad_apellido < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre debe de tener mas de 1 caracteres'
            ]);
            return;
        }

        $_POST['usuario_tel'] = filter_var($_POST['usuario_tel'], FILTER_VALIDATE_INT);

        if (strlen($_POST['usuario_tel']) != 8) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El telefono debe de tener 8 numeros'
            ]);
            return;
        }

        $_POST['usuario_direc'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_direc']))));
        $_POST['usuario_dpi'] = trim(htmlspecialchars($_POST['usuario_dpi']));

        if (strlen($_POST['usuario_dpi']) != 13) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de digitos del DPI debe de ser igual a 13'
            ]);
            return;
        }

        $_POST['usuario_correo'] = filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL);

        if (!filter_var($_POST['usuario_correo'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electronico no es valido'
            ]);
            return;
        }

        try {
            $data = Usuarios::find($id);
            $data->sincronizar([
                'usuario_nom1' => $_POST['usuario_nom1'],
                'usuario_nom2' => $_POST['usuario_nom2'],
                'usuario_ape1' => $_POST['usuario_ape1'],
                'usuario_ape2' => $_POST['usuario_ape2'],
                'usuario_tel' => $_POST['usuario_tel'],
                'usuario_direc' => $_POST['usuario_direc'],
                'usuario_dpi' => $_POST['usuario_dpi'],
                'usuario_correo' => $_POST['usuario_correo'],
                'usuario_situacion' => 1
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La informacion del usuario ha sido modificada exitosamente'
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
            $ejecutar = Usuarios::EliminarUsuarios($id);

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