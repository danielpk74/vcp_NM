<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class TemporalVentas extends CFormModel {
    /*
     * 
     * @param type $dias
     * @return type
     */
    public function TruncateTemporal() {
        $command = Yii::app()->db->createCommand();
        $command->truncateTable('TMP_VENTAS');
        
        return $ventas;
    }
    
    /**
     * Actualiza la tabla temporal de ventas del dia
     **/
    public function ActualizarTemporal()
    {
        $this->TruncateTemporal();
        
        $ventas = new Ventas();
        
        $plazas = new Plazas();
        $plazas = $plazas->get_Plazas();
        
        
//        
        foreach($plazas as $plaza) 
        {
            $ingresadas_plaza = $ventas->get_Ingresadas_Plaza_Fecha($plaza['PLAZA'], '2013-04-03');
            $instaladas_plaza = $ventas->get_Instaladas_Plaza_Fecha($plaza['PLAZA'], '2013-04-03');
            
            $ventas->set_Ingresadas_Instaladas($plaza['PLAZA'],$ingresadas_plaza['TOTAL_INGRESADA'],$instaladas_plaza['TOTAL_INSTALADA']);
        }
    }
}
