<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Presupuestos extends CFormModel {
    
    /**
     * 
     * @param string $tipoElemento
     * @param string $uen
     * @param type $anio
     * @param type $mes
     * @param string $plaza
     * @param type $consulta_general
     * @param type $consultaProducto
     * @param string $regional
     * @param string $plaza
     * @return Array
     */
    public function get_Presupuesto($tipoElemento, $uen = '', $anio = '', $mes = '', $plaza = '',$consulta_general='',$consultaProducto='',$regional='',$tipoCanal='') {
        // Si se consulta un mes especifico retorna un escalar
        if($mes != '')
            $presupuesto = Yii::app()->db->createCommand("SP_Presupuestos '$tipoElemento','$uen','$anio','$mes','$plaza','$consulta_general','$consultaProducto','$regional','$tipoCanal'")->queryScalar();
        
        // Si no se consulta un mes especifico agrupara el presupuesto por mes
        else
            $presupuesto = Yii::app()->db->createCommand("SP_Presupuestos '$tipoElemento','$uen','$anio','$mes','$plaza','$consulta_general','$consultaProducto','$regional','$tipoCanal'")->queryAll();
        
        return $presupuesto;
    }
}
