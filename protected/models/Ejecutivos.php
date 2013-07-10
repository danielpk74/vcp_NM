<?php

class Ejecutivos {
    /**
     * 
     * @param type $tipoElemento
     * @param type $tipoSolicitud
     * @param type $plaza
     * @param type $fecha
     * @param type $uen
     * @param type $consultaProducto
     * @param type $mes
     * @return type
     */
   public function get_Ingresadas_Instaladas($tipoElemento, $tipoSolicitud = 'Nuevo',$plaza = '', $fecha = '', $uen = '', $consultaProducto = '',$mes='',$nombreEjecutivo,$anio) {
        $ventas = Yii::app()->db->createCommand("SP_Instaladas_Ingresadas_Ejecutivo '1','$tipoElemento', '$tipoSolicitud','$plaza', '$fecha' , '$uen' , '$consultaProducto','$mes','$nombreEjecutivo','$anio'")->queryAll();
        return $ventas;
    }
}
