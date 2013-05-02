<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Uen extends CFormModel {
    
    public $UEN_ID;
    public $DESCRIPCION;

    public function get_UEN_Todas() {
        $uen = Yii::app()->db->createCommand()
                ->select('CODIGO_UEN_PK, DESCRIPCION')
                ->from('UEN')
                ->order('DESCRIPCION')
                ->queryAll();
        
        return $uen;
    }
}
