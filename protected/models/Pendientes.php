<?php


class Pendientes {
    
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
    public function TotalPendientes($tipoElemento, $uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '', $plaza = '', $regional = '', $anio = '', $tipoCanal = '') {
        $pendientes = Yii::app()->db->createCommand("SP_Total_Pendientes '1','$tipoElemento','$uen','$tipoSolicitud','$consultaProducto','$plaza','$regional','$anio','$tipoCanal'")->queryScalar();
        return $pendientes;
    }


    /**
     * Devuelve todos los Pendientes por regional
     * <br>ejecuta el SP: SP_Consultas_Ingresos_Retiros
     * @param type $tipoElemento
     * @param type $plaza
     * @param type $uen
     * @param type $tipoSolicitud
     * @param type $consultaProducto
     * @param type $mes
     * @param type $regional
     * @param type $tipoCanal
     * @return type
     */
    public function get_Pendientes_X_Regional($tipoElemento,$uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '1',  $plaza = '', $regional = '', $anio = '', $tipoCanal = '') {
        $pendientes = Yii::app()->db->createCommand("SP_Total_Pendientes '3','$tipoElemento','$uen','$tipoSolicitud','$consultaProducto','$plaza','$regional','$anio','$tipoCanal'")->queryAll();
        return $pendientes;
    }
    
    /**
     * Devuelve todos los Pendientes por regional
     * <br>ejecuta el SP: SP_Consultas_Ingresos_Retiros
     * @param type $tipoElemento
     * @param type $plaza
     * @param type $uen
     * @param type $tipoSolicitud
     * @param type $consultaProducto
     * @param type $mes
     * @param type $regional
     * @param type $tipoCanal
     * @return type
     */
    public function get_Pendientes_X_Responsables($tipoElemento,$uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '1',  $plaza = '', $regional = '', $anio = '', $tipoCanal = '') {
        $pendientes = Yii::app()->db->createCommand("SP_Total_Pendientes '4','$tipoElemento','$uen','$tipoSolicitud','$consultaProducto','$plaza','$regional','$anio','$tipoCanal'")->queryAll();
        return $pendientes;
    }
    
    /**
     * Devuelve todos los Pendientes por regional
     * <br>ejecuta el SP: SP_Consultas_Ingresos_Retiros
     * @param type $tipoElemento
     * @param type $plaza
     * @param type $uen
     * @param type $tipoSolicitud
     * @param type $consultaProducto
     * @param type $mes
     * @param type $regional
     * @param type $tipoCanal
     * @return type
     */
    public function get_Pendientes_X_Productos($tipoElemento,$uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '1',  $plaza = '', $regional = '', $anio = '', $tipoCanal = '') {
        $pendientes = Yii::app()->db->createCommand("SP_Total_Pendientes '5','$tipoElemento','$uen','$tipoSolicitud','$consultaProducto','$plaza','$regional','$anio','$tipoCanal'")->queryAll();
        return $pendientes;
    }
    
     /**
     * Devuelve todos los Pendientes por regional
     * <br>ejecuta el SP: SP_Consultas_Ingresos_Retiros
     * @param type $tipoElemento
     * @param type $plaza
     * @param type $uen
     * @param type $tipoSolicitud
     * @param type $consultaProducto
     * @param type $mes
     * @param type $regional
     * @param type $tipoCanal
     * @return type
     */
    public function get_Pendientes_X_Plazas($tipoElemento,$uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '1',  $plaza = '', $regional = '', $anio = '', $tipoCanal = '') {
        $pendientes = Yii::app()->db->createCommand("SP_Total_Pendientes '6','$tipoElemento','$uen','$tipoSolicitud','$consultaProducto','$plaza','$regional','$anio','$tipoCanal'")->queryAll();
        return $pendientes;
    }
}

