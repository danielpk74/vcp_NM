<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Plazas extends CFormModel {
    
    public $PLAZA;
    public $REGIONAL;
    public $DEPARTAMENTO;
    public $MUNICIPIO;
    

    public function get_Plazas($regional = '') {
        if ($regional != '') {
            $plazas = Yii::app()->db->createCommand()
                    ->selectDistinct('PLAZA')
                    ->from('PLAZAS')
                    ->where("REGIONAL IN(SELECT * FROM FS_Split(:regional))", array(':regional' => $regional))
                    ->order('PLAZA ASC')
                    ->queryAll();
        } else {
            $plazas = Yii::app()->db->createCommand()
                    ->selectDistinct('PLAZA')
                    ->from('PLAZAS')
                    ->queryAll();
        }
        
        return $plazas;
    }

}
