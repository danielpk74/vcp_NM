<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Presupuestos extends CFormModel {
    public function get_Presupuesto($tipoElemento, $uen = '', $anio = '', $mes = '', $plaza = '') {
        // echo $tipoElemento . " - " . $uen . " - ".$anio . " - " . $mes . " - " . $plaza;
        $presupuesto = Yii::app()->db->createCommand("SP_Presupuestos '$tipoElemento','$uen','$anio','$mes','$plaza'")->queryScalar();
        return $presupuesto;
    }
}
