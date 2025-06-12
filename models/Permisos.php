<?php

namespace Model;

use Model\ActiveRecord;

class Permisos extends ActiveRecord {
    
    public static $tabla = 'permiso';
    public static $idTabla = 'permiso_id';
    public static $columnasDB = 
    [
        'usuario_id',
        'app_id',
        'permiso_nombre',
        'permiso_clave',
        'permiso_desc',
        'permiso_tipo',
        'permiso_fecha',
        'permiso_usuario_asigno',
        'permiso_motivo',
        'permiso_situacion'
    ];
    
    public $permiso_id;
    public $usuario_id;
    public $app_id;
    public $permiso_nombre;
    public $permiso_clave;
    public $permiso_desc;
    public $permiso_tipo;
    public $permiso_fecha;
    public $permiso_usuario_asigno;
    public $permiso_motivo;
    public $permiso_situacion;
    
    public function __construct($permiso = [])
    {
        $this->permiso_id = $permiso['permiso_id'] ?? null;
        $this->usuario_id = $permiso['usuario_id'] ?? 0;
        $this->app_id = $permiso['app_id'] ?? 0;
        $this->permiso_nombre = $permiso['permiso_nombre'] ?? '';
        $this->permiso_clave = $permiso['permiso_clave'] ?? '';
        $this->permiso_desc = $permiso['permiso_desc'] ?? '';
        $this->permiso_tipo = $permiso['permiso_tipo'] ?? 'FUNCIONAL';
        $this->permiso_fecha = $permiso['permiso_fecha'] ?? '';
        $this->permiso_usuario_asigno = $permiso['permiso_usuario_asigno'] ?? 0;
        $this->permiso_motivo = $permiso['permiso_motivo'] ?? '';
        $this->permiso_situacion = $permiso['permiso_situacion'] ?? 1;
    }

    public static function EliminarPermiso($id){
        $sql = "DELETE FROM permiso WHERE permiso_id = $id";
        return self::SQL($sql);
    }

}