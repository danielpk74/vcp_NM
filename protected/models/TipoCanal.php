<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class TipoCanal extends CFormModel {
    
    public $CODIGO_TIPO_CANAL_PK;
    public $TIPO_CANAL;

    public function get_Tipo_Canal_Todos() {
        $tipoCanal = Yii::app()->db->createCommand()
                ->select('CODIGO_TIPO_CANAL_PK, TIPO_CANAL')
                ->from('TIPO_CANAL')
                ->order('TIPO_CANAL')
                ->queryAll();
        
        return $tipoCanal;
    }
}
