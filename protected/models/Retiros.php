<?php


class Retiros {
    
    /**
     * Devuelve el numero de ventas pendientes de instalacion 
     * @param type $tipoElemento
     * @param type $uen
     * @param type $tipoSolicitud
     * @param type $consultaProducto
     * @param type $plaza
     * @param type $regional
     * @param type $anio
     * @param type $tipoCanal
     * @return string Con el numero de pedidos pendientes por gestionar
     */
    public function get_Retiros_X_Mes($tipoElemento, $uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '', $plaza = '', $regional = '', $anio = '', $tipoCanal = '') {
        $retiros = Yii::app()->db->createCommand("SP_Retiros '2','$tipoElemento','$uen','$tipoSolicitud','$consultaProducto','$plaza','$regional','$anio','$tipoCanal'")->queryAll();
        return $retiros;
    }
}

