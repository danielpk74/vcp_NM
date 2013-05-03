<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Ventas {

    /**
     * Devuelve todos los ingresos e instalaciones agrupados por plaza.
     * @return type
     */
    public function Ingresadas($dias, $tipoElemento, $plaza = '', $fecha = '', $uen = '', $tipo_solicitud = 'Nuevo') {
        $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '3','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipo_solicitud'")->queryAll();
        return $ventas;
    }

    /**
     * Consulta los ingresos y las instalaciones de las plazas que no se muestran por separado(Otros).
     * @return type
     */
    public function IngresadasOtros($dias, $tipoElemento, $plaza = '', $fecha = '', $uen = '', $tipo_solicitud = 'Nuevo') {
        $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '4','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipo_solicitud'")->queryAll();
        return $ventas;
    }

    /**
     * Obtiene el numero de pedidos ingresados por plaza agrupado por fecha, partiendo desde 
     * el numero de dias enviados por parametros, hasta la fecha actual.
     * @param integer $dias el numero de dias desde que se debe traer el historial
     * @return array con los datos de los ingresos agrupados por fecha
     */
    public function get_Ingresadas($dias, $tipoElemento, $plaza = '', $fecha = '', $uen = '', $tipo_solicitud = 'Nuevo') {
        $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '1','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipo_solicitud'")->queryAll();
        return $ventas;
    }

    /**
     * Obtiene el numero de pedidos instalados por plaza agrupado por fecha, partiendo desde 
     * el numero de dias enviados por parametros, hasta la fecha actual.
     * @param integer $dias el numero de dias desde que se debe traer el historial
     * @return array con los datos de los ingresos agrupados por fecha
     */
    public function get_Instaladas($dias, $tipoElemento, $plaza = '', $fecha = '', $uen = '', $tipo_solicitud = 'Nuevo') {
        $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '2','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipo_solicitud'")->queryAll();
        return $ventas;
    }

    /**
     * Devuelve el numero de pedidos ingresados en el mes actual
     * @return string numero de ingresados
     */
    public function get_TotalIngresadasMes($tipoElemento, $uen = '', $tipo_solicitud = 'Nuevo') {
        $ventasIngresadasMes = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '1','$tipoElemento','$uen','$tipo_solicitud'")->queryScalar();
        return $ventasIngresadasMes;
    }

    /**
     * Devuelve el numero de pedidos instalados en el mes actual
     * @return string numero de instalados
     */
    public function get_TotalInstaladasMes($tipoElemento, $uen = '', $tipo_solicitud = 'Nuevo',$tipoDia='',$plaza='') {
        $ventasInstaladasMes = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '2','$tipoElemento','$uen','$tipo_solicitud','$tipoDia','$plaza'")->queryScalar();
        return $ventasInstaladasMes;
    }

    /**
     * Calcula el numero de instalaciones aproximadas en las que cerrara el mes actual
     * @param string $tipoElemento
     * @param string $uen
     * @param string $tipo_solicitud
     * @return string
     */
    public static function get_ProyectadoMes($tipoElemento, $uen = '', $tipo_solicitud = '') {
        $totalInstaladasMes = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '2','$tipoElemento','$uen','$tipo_solicitud',''")->queryScalar();

        $totalInstaladasDiasHabiles = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '2','$tipoElemento','$uen','$tipo_solicitud','s'")->queryScalar();
        $totalInstaladasDiasFinSemana = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '2','$tipoElemento','$uen','$tipo_solicitud','e'")->queryScalar();
        $totalInstaladasDiasFestivos = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '2','$tipoElemento','$uen','$tipo_solicitud','f'")->queryScalar();

        $totalDiasHabiles = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '3','','','','s'")->queryAll();
        $totalDiasFinSemana = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '3','','','','e'")->queryAll();
        $totalDiasFestivos = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '3','','','','f'")->queryAll();

        // Se suman los festivos y los fines de semana por sugerencia de Mauricio Cano, "el comportamiento es muy similar"
        $totalDiasFinSemana = $totalDiasFinSemana + $totalDiasFestivos;

        // Promedio de instalaciones en dias habiles
        if (Count($totalDiasHabiles) != 0)
            $promedioInstaladasDiasHabiles = $totalInstaladasDiasHabiles / Count($totalDiasHabiles);

        // Promedio de instalaciones en dias fin de semana
        if (Count($totalDiasFinSemana) != 0)
            $promedioInstaladasDiasFestivos = $totalInstaladasDiasFinSemana / Count($totalDiasFinSemana);

        $numeroDiasHabilesFaltantes = FunsionesSoporte::get_DiasFaltantesMes(1);
        $numeroDiasFestivosFaltantes = FunsionesSoporte::get_DiasFaltantesMes(2);

        // Presupuesto Mensual
        if ($numeroDiaActual <= 10) {
            $proyectadoCierre = new Presupuestos();
            $proyectadoCierre = $proyectadoCierre->get_Presupuesto($tipoElemento, $uen, date('Y'),date('n'),'');
        }
        // TotalInstaladas en el mes + promedio instalado en dias habiles * dias habiles faltantes + promedio instaladas en dias festivos y fines de semana * Dias festivos faltantes + total de pendientes(restando 25% de estos).
        elseif ($numeroDiaActual >= 11 && $numeroDiaActual <= 26) {
            $ventas = new Ventas();
            $totalPendientes = $ventas->TotalPendientes($tipoElemento, $uen, $tipo_solicitud);
            $totalPendientes = ($totalPendientes - ($totalPendientes * 0.25));
            $proyectadoCierre = ($totalInstaladasMes + ($promedioInstaladasDiasHabiles * $numeroDiasHabilesFaltantes) + ($promedioInstaladasDiasFestivos * $numeroDiasFestivosFaltantes)) + $totalPendientes;
        } else {
            $proyectadoCierre = $totalInstaladasMes + ($promedioInstaladasDiasHabiles * $numeroDiasHabilesFaltantes) + ($promedioInstaladasDiasFestivos * $numeroDiasFestivosFaltantes);
        }

//        $proyectadoCierre = $totalInstaladasMes + ($promedioInstaladasDiasHabiles * $numeroDiasHabilesFaltantes) + ($promedioInstaladasDiasFestivos * $numeroDiasFestivosFaltantes);
        return $proyectadoCierre;
    }

    /**
     * Devuelve el numero de 
     * @param type $tipoElemento
     * @param type $uen
     * @param type $tipo_solicitud
     * @return string Con el numero de pedidos pendientes por gestionar
     */
    public function TotalPendientes($tipoElemento, $uen = '', $tipo_solicitud = 'Nuevo') {
        $pendientes = Yii::app()->db->createCommand("SP_Total_Pendientes '$tipoElemento','$uen','$tipo_solicitud'")->queryScalar();
        return $pendientes;
    }

}

