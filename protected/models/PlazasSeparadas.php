<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class PlazasSeparadas extends CFormModel {

    public static function get_PlazaSeparada($nombrePlaza) {
        $Plazas = Yii::app()->db->createCommand()
                ->select('Count(PLAZA) PLAZA')
                ->from('PLAZAS_SEPARADAS')
                ->where("PLAZA = '$nombrePlaza'")
                ->queryScalar();
                
        if ($Plazas != 0)
            return true;
        else
            return false;
    }

}
