<?php

namespace Model;

use Model\ActiveRecord;

class Aplicacion extends ActiveRecord {
    
    public static $tabla = 'aplicacion';
    public static $idTabla = 'app_id';
    public static $columnasDB = 
    [
        'app_nombre_largo',
        'app_nombre_medium',
        'app_nombre_corto',
        'app_fecha_creacion',
        'app_situacion'
    ];
    
    public $app_id;
    public $app_nombre_largo;
    public $app_nombre_medium;
    public $app_nombre_corto;
    public $app_fecha_creacion;
    public $app_situacion;
    
    public function __construct($aplicacion = [])
    {
        $this->app_id = $aplicacion['app_id'] ?? null;
        $this->app_nombre_largo = $aplicacion['app_nombre_largo'] ?? '';
        $this->app_nombre_medium = $aplicacion['app_nombre_medium'] ?? '';
        $this->app_nombre_corto = $aplicacion['app_nombre_corto'] ?? '';
        $this->app_fecha_creacion = $aplicacion['app_fecha_creacion'] ?? '';
        $this->app_situacion = $aplicacion['app_situacion'] ?? 1;
    }

    public static function EliminarAplicaciones($id){
        $sql = "UPDATE aplicacion SET app_situacion = 0 WHERE app_id = $id";
        return self::SQL($sql);
    }

}