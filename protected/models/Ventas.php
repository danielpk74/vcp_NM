<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Ventas extends CFormModel {

    /**
     * Consulta la tabla temporal de resumen de ventas.
     * @return type
     */
    public function Ingresadas() {
        $ventas = Yii::app()->db->createCommand()
                ->select('*')
                ->from('TMP_VENTAS')
                ->queryAll();
        
        return $ventas;
    }

    /**
     * Devuelve el numero de pedidos ingresados para una plaza en una fecha determinada
     * <br><b>Utiliza el SP_Ingresadas_X_Plaza_X_Fecha</b>
     * @param string $plaza
     * @param string $fecha_ingreso
     * @param string $producto
     * @return string Numero total de ingresadas
     */
    public function get_Ingresadas_Plaza_Fecha($plaza, $fecha_ingreso,$tipoElemento) {
        $plaza = (string) "''" . $plaza . "''";
        $fecha_ingreso = (string) "''" . $fecha_ingreso . "''";
        $tipoElemento = (string) "''" . $tipoElemento . "''";

        $ventas = Yii::app()->db->createCommand("SP_Ingresadas_X_Plaza_X_Fecha '$plaza','$fecha_ingreso','$tipoElemento'")->queryRow();
        return $ventas;
    }

    /**
     * Devuelve el numero de pedidos instalados para una plaza en una fecha determinada
     * @param string $plaza 
     * @param string $fecha_ingreso
     * @return string Numero total de instaladas
     */
    public function get_Instaladas_Plaza_Fecha($plaza, $fecha_ingreso,$tipoElemento) {
        $plaza = (string) "''" . $plaza . "''";
        $fecha_ingreso = (string) "''" . $fecha_ingreso . "''";
        $tipoElemento = (string) "''" . $tipoElemento . "''";

        $ventas = Yii::app()->db->createCommand("SP_Instaladas_X_Plaza_X_Fecha '$plaza','$fecha_ingreso','$tipoElemento'")->queryRow();
        return $ventas;
    }

    /**
     * Devuelve los pedidos ingresados
     * @param type $dias
     * @return objeto
     */
    public function get_Ingresadas($dias,$tipoElemento) {
        $tipoElemento = (string) "''" . $tipoElemento . "''";
        $ventas = Yii::app()->db->createCommand("SP_Ingresadas_X_Plaza_X_Dias '$dias','$tipoElemento'")->queryAll();
        return $ventas;
    }

    /**
     * Obtiene el numero de pedidos instalados por plaza agrupado por fecha, partiendo desde 
     * el numero de dias enviados por parametros, hasta la fecha actual.
     * @param integer $dias el numero de dias desde que se debe traer el historial
     * @return array con los datos de los ingresos agrupados por fecha
     */
    public function get_Instaladas($dias,$tipoElemento) {
        $tipoElemento = (string) "''" . $tipoElemento . "''";
        $ventas = Yii::app()->db->createCommand("SP_Instaladas_X_Plaza_X_Dias '$dias','$tipoElemento'")->queryAll();
        return $ventas;
    }

    /**
     * Truncate a la tabla
     */
    public function TruncateTemporalVentas($nombreTabla) {
        $ingresadasPlaza = Yii::app()->db->createCommand()->truncateTable((string) $nombreTabla);
    }

    /**
     * * Inserta los pedidos ingresados e instaladas en una plaza determinada
     * @param string $plaza La plaza a insertar
     * @param integer $totaIngresadas El numero de pedidos ingresados de la plaza
     * @param integer $totalInstaladas El numero de pedidos instalados de la plaza
     */
    public function set_Ingresadas_Instaladas($plaza, $totaIngresadas, $totalInstaladas) {
        $command = Yii::app()->db->createCommand();
        $command->insert('TMP_VENTAS', array(
            'PLAZA' => $plaza,
            'INGRESADAS' => $totaIngresadas,
            'INSTALADAS' => $totalInstaladas,
        ));
    }

    /**
     * Inserta o actualiza(si fue insertado un registro previamente) los pedidos ingresados o instalados
     * en las plazas que no son consideradas como principales(entidad PLAZAS_SEPARADAS)
     */
    public function set_Ingresadas_Instaladas_Otros($totaIngresadas, $totalInstaladas) {
        $command = Yii::app()->db->createCommand();

        $otros = Yii::app()->db->createCommand()
                ->select('COUNT(*)')
                ->from('TMP_VENTAS')
                ->where('PLAZA = :plaza',array('plaza'=>'OTROS'))
                ->queryScalar();

        if ($otros == 0) {
            $command->insert('TMP_VENTAS', array(
                'PLAZA' => 'Otros',
                'INGRESADAS' => $totaIngresadas,
                'INSTALADAS' => $totalInstaladas,
            ));
        } else {
            $command->update('TMP_VENTAS', array(
                'INGRESADAS' => $otros['INGRESADAS'] + $totaIngresadas,
                'INSTALADAS' => $otros['INSTALADAS'] + $totalInstaladas,
                    ), 'PLAZA=:plaza', array(':plaza' => 'OTROS'));
        }
    }

    /**
     * Devuelve el numero de pedidos ingresados en el mes actual
     * @return string numero de ingresados
     */
    public function TotalIngresadasMes($tipoElemento) {
        $tipoElemento = (string) "''" . $tipoElemento . "''";
        $ventasIngresadasMes = Yii::app()->db->createCommand("SP_Ingresadas_X_Mes '$tipoElemento'")->queryScalar();
        return $ventasIngresadasMes;
    }

    /**
     * Devuelve el numero de pedidos instalados en el mes actual
     * @return string numero de instalados
     */
    public function TotalInstaladasMes($tipoElemento) {
        $tipoElemento = (string) "''" . $tipoElemento . "''";
        $ventasInstaladasMes = Yii::app()->db->createCommand("SP_Instaladas_X_Mes '$tipoElemento'")->queryScalar();
        return $ventasInstaladasMes;
    }

    /**
     * Devuelve el numero de 
     * @return string Con el numero de pedidos pendientes por gestionar
     */
    public function TotalPendientes($tipoElemento) {
        $ventas = Yii::app()->db->createCommand()
                ->select('COUNT(PEDIDO_ID) as TOTAL')
                ->from('PEDIDOS_MOVILIDAD')
                ->where('ESTADO=:estado',array(':estado'=>'Pendiente'))
                ->andwhere('TIPO_SOLICITUD=:tipoSolicitud',array(':tipoSolicitud'=>'NUEVO'))
                ->andwhere('TIPO_ELEMENTO_ID=:tipoElemento',array(':tipoElemento'=>$tipoElemento))
                ->queryScalar();
        
        return $ventas;
    }
}
