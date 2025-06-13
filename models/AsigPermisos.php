<?php

namespace Model;

use Model\ActiveRecord;

class AsigPermisos extends ActiveRecord {

    public static $tabla = 'asig_permisos';
    public static $idTabla = 'asignacion_id';
    public static $columnasDB = [
        'asignacion_usuario_id',
        'asignacion_app_id',
        'asignacion_permiso_id',
        'asignacion_fecha_asignar',
        'asignacion_fecha_quitar',
        'asignacion_usuario_asigno',
        'asignacion_motivo',
        'asignacion_situacion'
    ];
    

    public $asignacion_id;
    public $asignacion_usuario_id;
    public $asignacion_app_id;
    public $asignacion_permiso_id;
    public $asignacion_fecha_asignar;
    public $asignacion_fecha_quitar;
    public $asignacion_usuario_asigno;
    public $asignacion_motivo;
    public $asignacion_situacion;
    

    public function __construct($asignacion = [])
    {
        $this->asignacion_id = $asignacion['asignacion_id'] ?? null;
        $this->asignacion_usuario_id = $asignacion['asignacion_usuario_id'] ?? 0;
        $this->asignacion_app_id = $asignacion['asignacion_app_id'] ?? 0;
        $this->asignacion_permiso_id = $asignacion['asignacion_permiso_id'] ?? 0;
        $this->asignacion_fecha_asignar = $asignacion['asignacion_fecha_asignar'] ?? '';
        $this->asignacion_fecha_quitar = $asignacion['asignacion_fecha_quitar'] ?? '';
        $this->asignacion_usuario_asigno = $asignacion['asignacion_usuario_asigno'] ?? 0;
        $this->asignacion_motivo = $asignacion['asignacion_motivo'] ?? '';
        $this->asignacion_situacion = $asignacion['asignacion_situacion'] ?? 1;
    }


    public static function EliminarAsignacion($id){
        $sql = "UPDATE asig_permisos SET asignacion_situacion = 0 WHERE asignacion_id = $id";
        return self::SQL($sql);
    }


    public static function QuitarAsignacion($id){
        $sql = "DELETE FROM asig_permisos WHERE asignacion_id = $id";
        return self::SQL($sql);
    }


    public static function VerificarAsignacion($usuario_id, $app_id, $permiso_id){
        $sql = "SELECT COUNT(*) as total FROM asig_permisos 
                WHERE asignacion_usuario_id = $usuario_id 
                AND asignacion_app_id = $app_id 
                AND asignacion_permiso_id = $permiso_id 
                AND asignacion_situacion = 1";
        
        $resultado = self::fetchFirst($sql);
        return isset($resultado['total']) && $resultado['total'] > 0;
    }


    public static function ObtenerActivas(){
        $sql = "SELECT * FROM asig_permisos WHERE asignacion_situacion = 1 ORDER BY asignacion_fecha_asignar DESC";
        return self::fetchArray($sql);
    }


    public static function ObtenerPorUsuario($usuario_id){
        $sql = "SELECT * FROM asig_permisos WHERE asignacion_usuario_id = $usuario_id AND asignacion_situacion = 1";
        return self::fetchArray($sql);
    }


    public static function ObtenerPorAplicacion($app_id){
        $sql = "SELECT * FROM asig_permisos WHERE asignacion_app_id = $app_id AND asignacion_situacion = 1";
        return self::fetchArray($sql);
    }
}