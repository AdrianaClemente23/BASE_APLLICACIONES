<?php

namespace Model;

class HistorialAct extends ActiveRecord {

    public static $tabla = 'historial_act';
    public static $columnasDB = [
        'historial_usuario_id',
        'historial_fecha',
        'historial_ruta',
        'historial_ejecucion',
        'historial_situacion'
    ];

    public static $idTabla = 'historial_id';
    public $historial_id;
    public $historial_usuario_id;
    public $historial_fecha;
    public $historial_ruta;
    public $historial_ejecucion;
    public $historial_situacion;

    public function __construct($args = []){
        $this->historial_id = $args['historial_id'] ?? null;
        $this->historial_usuario_id = $args['historial_usuario_id'] ?? 0;
        $this->historial_fecha = $args['historial_fecha'] ?? date('Y-m-d H:i:s');
        $this->historial_ruta = $args['historial_ruta'] ?? 0;
        $this->historial_ejecucion = $args['historial_ejecucion'] ?? '';
        $this->historial_situacion = $args['historial_situacion'] ?? 1;
    }

    public static function EliminarHistorial($id){
        $sql = "DELETE FROM historial_act WHERE historial_id = $id";
        return self::SQL($sql);
    }

    public static function ObtenerActivo(){
        $sql = "SELECT * FROM historial_act WHERE historial_situacion = 1 ORDER BY historial_fecha DESC";
        return self::SQL($sql);
    }

    public static function ObtenerPorUsuario($usuario_id){
        $sql = "SELECT * FROM historial_act WHERE historial_usuario_id = $usuario_id AND historial_situacion = 1 ORDER BY historial_fecha DESC";
        return self::SQL($sql);
    }

    public static function ObtenerPorRuta($ruta_id){
        $sql = "SELECT * FROM historial_act WHERE historial_ruta = $ruta_id AND historial_situacion = 1 ORDER BY historial_fecha DESC";
        return self::SQL($sql);
    }

    public static function ObtenerPorFechas($fecha_inicio, $fecha_fin){
        $sql = "SELECT * FROM historial_act WHERE historial_fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' AND historial_situacion = 1 ORDER BY historial_fecha DESC";
        return self::SQL($sql);
    }

    public static function RegistrarActividad($usuario_id, $ruta_id, $ejecucion){
        $sql = "INSERT INTO historial_act (historial_usuario_id, historial_ruta, historial_ejecucion, historial_fecha, historial_situacion) 
                VALUES ($usuario_id, $ruta_id, '$ejecucion', NOW(), 1)";
        return self::SQL($sql);
    }
}