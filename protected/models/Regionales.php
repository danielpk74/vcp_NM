<?php

class Regionales extends CFormModel {
    
    public $CODIGO_REGIONAL_PK;
    public $REGIONAL;
    
    /**
     * Consulta la tabla regionales
     * @return type
     */
    public function get_Regionales() {
      
        $regionales = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('REGIONALES')
                    ->order('REGIONAL ASC')
                    ->queryAll();

        return $regionales;
    }
}
