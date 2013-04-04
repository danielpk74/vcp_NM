<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Plazas extends CFormModel {

    public function get_Plazas() {
        $Plazas = Yii::app()->db->createCommand()
                -> selectDistinct('PLAZAS')
                ->from('MOVILIDAD_4G-LTE_INFORME_DIARIO')
                ->queryAll();
        
        return $Plazas;
    }
}
