<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Plazas extends CFormModel {

    public function get_Plazas() {
        $plazas = Yii::app()->db->createCommand()
                ->selectDistinct('PLAZA')
                ->from('PLAZAS')
                ->queryAll();

        
        
        return $plazas;
    }
}
