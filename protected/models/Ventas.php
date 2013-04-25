<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Ventas extends CFormModel {

    /**
     * Devuelve todos los ingresos e instalaciones agrupados por plaza.
     * @return type
     */
    public function Ingresadas($dias, $tipoElemento,$plaza='',$fecha='',$uen='',$tipo_solicitud='Nuevo') {
//        if ($tipoElemento != "ALL") {
            $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '3','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipo_solicitud'")->queryAll();
//        } else {
//            $ventas = Yii::app()->db->createCommand()
//                    ->select('PLAZA, SUM(INGRESADAS) AS INGRESADAS, SUM(INSTALADAS) AS INSTALADAS')
//                    ->from('TMP_VENTAS')
//                    ->where('TIPO_ELEMENTO_ID <> :tipoelemento', array('tipoelemento' => $tipoElemento))
//                    ->group('PLAZA')
//                    ->queryAll();
//        }

        return $ventas;
    }
    
    /**
     * Consulta los ingresos y las instalaciones de las plazas que no se muestran por separado(Otros).
     * @return type
     */
    public function IngresadasOtros($dias, $tipoElemento,$plaza='',$fecha='',$uen='',$tipo_solicitud='Nuevo') {
//        if ($tipoElemento != "ALL") {
            $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '4','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipo_solicitud'")->queryAll();
//        } else {
//            $ventas = Yii::app()->db->createCommand()
//                    ->select('PLAZA, SUM(INGRESADAS) AS INGRESADAS, SUM(INSTALADAS) AS INSTALADAS')
//                    ->from('TMP_VENTAS')
//                    ->where('TIPO_ELEMENTO_ID <> :tipoelemento', array('tipoelemento' => $tipoElemento))
//                    ->group('PLAZA')
//                    ->queryAll();
//        }

        return $ventas;
    }

    /**
     * Obtiene el numero de pedidos ingresados por plaza agrupado por fecha, partiendo desde 
     * el numero de dias enviados por parametros, hasta la fecha actual.
     * @param integer $dias el numero de dias desde que se debe traer el historial
     * @return array con los datos de los ingresos agrupados por fecha
     */
    public function get_Ingresadas($dias, $tipoElemento,$plaza='',$fecha='',$uen='',$tipo_solicitud='Nuevo') {
        $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '1','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipo_solicitud'")->queryAll();
        return $ventas;
    }

    /**
     * Obtiene el numero de pedidos instalados por plaza agrupado por fecha, partiendo desde 
     * el numero de dias enviados por parametros, hasta la fecha actual.
     * @param integer $dias el numero de dias desde que se debe traer el historial
     * @return array con los datos de los ingresos agrupados por fecha
     */
   public function get_Instaladas($dias, $tipoElemento,$plaza='',$fecha='',$uen='',$tipo_solicitud='Nuevo') {
        $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '2','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipo_solicitud'")->queryAll();
        return $ventas;
    }
   
    /**
     * Devuelve el numero de pedidos ingresados en el mes actual
     * @return string numero de ingresados
     */
    public function TotalIngresadasMes($tipoElemento,$uen='',$tipo_solicitud='Nuevo') {
        $ventasIngresadasMes = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '1','$tipoElemento','$uen','$tipo_solicitud'")->queryScalar();       
        return $ventasIngresadasMes;
    }

    /**
     * Devuelve el numero de pedidos instalados en el mes actual
     * @return string numero de instalados
     */
    public function TotalInstaladasMes($tipoElemento,$uen='',$tipo_solicitud='Nuevo') {
        
        $ventasInstaladasMes = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '2','$tipoElemento','$uen','$tipo_solicitud'")->queryScalar();       
        return $ventasInstaladasMes;
    }

    /**
     * Devuelve el numero de 
     * @param type $tipoElemento
     * @param type $uen
     * @param type $tipo_solicitud
     * @return string Con el numero de pedidos pendientes por gestionar
     */
    public function TotalPendientes($tipoElemento,$uen='',$tipo_solicitud='Nuevo') {
        $pendientes = Yii::app()->db->createCommand("SP_Total_Pendientes '$tipoElemento','$uen','$tipo_solicitud'")->queryScalar();
        return $pendientes;
    }
}

