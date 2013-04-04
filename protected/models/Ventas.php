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
//
////          $comando = Yii::app()-> db->createCommand('SP_Ingresadas_X_Plaza_X_Dias(1)')->queryColumn();
//        //executar e pegar o resultado
////      $command = Yii::app()->db->createCommand("SP_Ingresadas_X_Plaza_X_Dias(:dias,@out)");
//        
////        $command = Yii::app()->db->createCommand("SP_Ingresadas_X_Plaza_X_Dias(:dias)")->queryAll(true, array(':dias'=>50));
//        
//        $command = Yii::app()->db->createCommand("SP_Ingresadas_X_Plaza_X_Dias(?)");
//        $value = 50;
//        $command->bindParam(1, $value, PDO::PARAM_INT, 4000); 
//        $command->queryAll();
//        var_dump($command);
        
//      $command->excecute();
//      var_dump($command->fecthAll());
//      $resultado = Yii::app()->db->createCommand("select @out as result;")->queryScalar();

        return $ventas;     
    }
    
    public function get_Ingresadas($dias = 7)
    {
        $ventas = Yii::app()->db->createCommand()
                ->select('*')
                ->from('V_INGRESADAS_7')
                ->queryAll();
        
        return $ventas;     
    }
    
     public function get_Instaladas($dias = 7)
    {
        $ventas = Yii::app()->db->createCommand()
                ->select('*')
                ->from('V_INSTALADAS_7')
                ->queryAll();
        
        return $ventas;     
    }

    /**
     * Devuelve el total de ingresos para la plaza enviada por parametros
     * @param string $plaza
     * @return array
     */
    public function IngresadasPlaza($plaza, $dias) {
        $ingresadasPlaza = Yii::app()->db->createCommand()
                ->select('*')
                ->from('V_INGRESADAS_' . $dias)
                ->where('PLAZA=:plaza', array(':plaza' => $plaza))
                ->queryAll();

        return $ventas;
    }

    /**
     * Truncate a la tabla
     */
    public function TruncateTemporalVentas($nombreTabla) {
        $ingresadasPlaza = Yii::app()->db->createCommand()->truncateTable((string) $nombreTabla);
    }
}
