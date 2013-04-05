<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Ventas extends CFormModel {

    public function Ingresadas() {
        $ventas = Yii::app()->db->createCommand()
                ->select('*')
                ->from('TMP_VENTAS')
                ->queryAll();
        
        $dias = 10;
//      $ventas = Yii::app()->db->createCommand("SP_Ingresadas_X_Plaza_X_Dias '$dias'")->queryAll();
        
        return $ventas;
    }

    public function get_Ingresadas_Plaza_Fecha($plaza,$fecha_ingreso)
    {
        $plaza = (string)"''".$plaza."''";
        $fecha_ingreso = (string)"''".$fecha_ingreso."''";
        
        $ventas = Yii::app()->db->createCommand("SP_Ingresadas_X_Plaza_X_Fecha '$plaza','$fecha_ingreso'")->queryRow();
        return $ventas;
    }
    
    public function get_Instaladas_Plaza_Fecha($plaza,$fecha_ingreso)
    {
        $plaza = (string)"''".$plaza."''";
        $fecha_ingreso = (string)"''".$fecha_ingreso."''";
        
        $ventas = Yii::app()->db->createCommand("SP_Instaladas_X_Plaza_X_Fecha '$plaza','$fecha_ingreso'")->queryRow();
        return $ventas;
    }
    
    /**
     * Devuelve los pedidos ingresados
     * @param type $dias
     * @return type
     */
    public function get_Ingresadas($dias) {
//        $ventas = Yii::app()->db->createCommand()
//                ->select('*')
//                ->from('V_INGRESADAS_7')
//                ->queryAll();

        $ventas = Yii::app()->db->createCommand("SP_Ingresadas_X_Plaza_X_Dias '$dias'")->queryAll();
        return $ventas;
    }

    /**
     * 
     * @param type $dias
     * @return type
     */
    public function get_Instaladas($dias) {
//        $ventas = Yii::app()->db->createCommand()
//                ->select('*')
//                ->from('V_INSTALADAS_7')
//                ->queryAll();

        $ventas = Yii::app()->db->createCommand("SP_Instaladas_X_Plaza_X_Dias '$dias'")->queryAll();
        return $ventas;
    }

    /**
     * Truncate a la tabla
     */
    public function TruncateTemporalVentas($nombreTabla) {
        $ingresadasPlaza = Yii::app()->db->createCommand()->truncateTable((string) $nombreTabla);
    }
    
    /**
     * Truncate a la tabla
     */
    public function set_Ingresadas_Instaladas($plaza,$totaIngresadas,$totalInstaladas) {
        $command = Yii::app()->db->createCommand();
        $command->insert('TMP_VENTAS', array(
                         'PLAZA'=>$plaza,
                         'INGRESADAS'=>$totaIngresadas,
                         'INSTALADAS'=>$totalInstaladas,
        ));
    }
}
