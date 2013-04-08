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

//      $ventas = Yii::app()->db->createCommand("SP_Ingresadas_X_Plaza_X_Dias '$dias'")->queryAll();
        return $ventas;
    }

    /**
     * Devuelve el numero de pedidos ingresados para una plaza en una fecha determinada
     * @param string $plaza
     * @param string $fecha_ingreso
     * @return string Numero total de ingresadas
     */
    public function get_Ingresadas_Plaza_Fecha($plaza, $fecha_ingreso) {
        $plaza = (string) "''" . $plaza . "''";
        $fecha_ingreso = (string) "''" . $fecha_ingreso . "''";

        $ventas = Yii::app()->db->createCommand("SP_Ingresadas_X_Plaza_X_Fecha '$plaza','$fecha_ingreso'")->queryRow();
        return $ventas;
    }

    /**
     * Devuelve el numero de pedidos instalados para una plaza en una fecha determinada
     * @param string $plaza 
     * @param string $fecha_ingreso
     * @return string Numero total de instaladas
     */
    public function get_Instaladas_Plaza_Fecha($plaza, $fecha_ingreso) {
        $plaza = (string) "''" . $plaza . "''";
        $fecha_ingreso = (string) "''" . $fecha_ingreso . "''";

        $ventas = Yii::app()->db->createCommand("SP_Instaladas_X_Plaza_X_Fecha '$plaza','$fecha_ingreso'")->queryRow();
        return $ventas;
    }

    /**
     * Devuelve los pedidos ingresados
     * @param type $dias
     * @return objeto
     */
    public function get_Ingresadas($dias) {
        $ventas = Yii::app()->db->createCommand("SP_Ingresadas_X_Plaza_X_Dias '$dias'")->queryAll();
        return $ventas;
    }

    /**
     * 
     * @param type $dias
     * @return type
     */
    public function get_Instaladas($dias) {
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
                ->where("PLAZA = 'OTROS'")
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
    public function TotalIngresadasMes() {
        $ventasIngresadasMes = Yii::app()->db->createCommand("SP_Ingresadas_X_Mes")->queryScalar();
        return $ventasIngresadasMes;
    }

    /**
     * Devuelve el numero de pedidos instalados en el mes actual
     * @return string numero de instalados
     */
    public function TotalInstaladasMes() {
        $ventasInstaladasMes = Yii::app()->db->createCommand("SP_Instaladas_X_Mes")->queryScalar();
        return $ventasInstaladasMes;
    }

    /**
     * Devuelve el numero de 
     * @return type
     */
    public function TotalPendientes() {
        $ventas = Yii::app()->db->createCommand()
                ->select('COUNT(PEDIDO_ID) as TOTAL')
                ->from('MOVILIDAD_4G-LTE_INFORME_DIARIO')
                ->where('ESTADO=:estado',array(':estado'=>'Pendiente'))
                ->andwhere('TIPO_SOLICITUD=:tipoSolicitud',array(':tipoSolicitud'=>'NUEVO'))
                ->queryScalar();
        
        return $ventas;
    }
}
