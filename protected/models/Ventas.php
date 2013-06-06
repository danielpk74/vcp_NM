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
    public function Ingresadas($dias, $tipoElemento, $plaza = '', $fecha = '', $uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '') {
        $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '3','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipoSolicitud','$consultaProducto'")->queryAll();
        return $ventas;
    }

    /**
     * Consulta los ingresos y las instalaciones de las plazas que no se muestran por separado(Otros).
     * @param integer $dias El numero de dias que se desea consultar desde la fecha hacia atras (ejem: los ultimos 7 dias), parametro requerido cuando se quiere generar grafico por dias
     * @param varchar $tipoElemento Tipo elemento a consultar (ejem: NUMMOV)
     * @param varchar $plaza La plaza que se desea consultar
     * @param date $fecha La fecha que se quiere consultar, parametro requerido cuando se desea ver los ingresos e instalaciones de una fecha especifica
     * @param varchar $uen La uen que se desea consultar (Pymes,Corporativos,Hogares)
     * @param varchar $tipoSolicitud Tipos de Solicitud (Nuevo,Retiro)
     * @return array
     */
    public function IngresadasOtros($dias, $tipoElemento, $plaza = '', $fecha = '', $uen = '', $tipoSolicitud = 'Nuevo', $mes, $consultaProducto = '') {
        $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '4','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipoSolicitud','$consultaProducto','$mes'")->queryAll();
        return $ventas;
    }

    /**
     * Obtiene el numero de pedidos ingresados por plaza agrupado por fecha, partiendo desde 
     * el numero de dias enviados por parametros, hasta la fecha actual.
     * @param integer $dias el numero de dias desde que se debe traer el historial
     * @param string $tipoElemento Codigo con el que se encuentra en FENIX el producto de 4G O 3G, NUMMOV, LIMOV, TO, ETC
     * @param string $plaza
     * @param date $fecha
     * @param string $uen
     * @param string $tipoSolicitud
     * @param integer $consultaProducto
     * @return array con los datos de los ingresos agrupados por fecha
     */
    public function get_Ingresadas($dias, $tipoElemento, $plaza = '', $fecha = '', $uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '') {
        $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '1','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipoSolicitud','$consultaProducto'")->queryAll();
        return $ventas;
    }

    /**
     * Obtiene el numero de pedidos instalados por plaza agrupado por fecha, partiendo desde 
     * el numero de dias enviados por parametros, hasta la fecha actual.
     * @param integer $dias el numero de dias desde que se debe traer el historial
     * @return array con los datos de los ingresos agrupados por fecha
     */
    public function get_Instaladas($dias, $tipoElemento, $plaza = '', $fecha = '', $uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '') {
        $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '2','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipoSolicitud','$consultaProducto'")->queryAll();
        return $ventas;
    }

    /**
     * Devuelve el numero de pedidos ingresados en el mes actual
     * @return string numero de ingresados
     */
    public function get_TotalIngresadasMes($tipoElemento, $uen = '', $tipoSolicitud = 'Nuevo', $tipoDia = '', $plaza = '',$mes, $consultaProducto = '') {
        $ventasIngresadasMes = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '1','$tipoElemento','$uen','$tipoSolicitud','$tipoDia','$plaza','$mes','$consultaProducto'")->queryScalar();
        return $ventasIngresadasMes;
    }

    /**
     * Devuelve el numero de pedidos instalados en el mes actual
     * @return string numero de instalados
     */
    public function get_TotalInstaladasMes($tipoElemento, $uen = '', $tipoSolicitud = 'Nuevo', $tipoDia = '', $plaza = '',$mes, $consultaProducto = '') {
        $ventasInstaladasMes = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '2','$tipoElemento','$uen','$tipoSolicitud','$tipoDia','$plaza','$mes','$consultaProducto'")->queryScalar();
        return $ventasInstaladasMes;
    }

    /**
     * Calcula el numero de instalaciones aproximadas en las que cerrara el mes actual
     * @param string $tipoElemento
     * @param string $uen
     * @param string $tipoSolicitud
     * @return string
     */
    public static function get_ProyectadoMes($tipoElemento, $uen = '', $tipoSolicitud = '', $tipoDia = '', $plaza = '', $consultaProducto = '') {
        $totalInstaladasMes = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '2','$tipoElemento','$uen','$tipoSolicitud','','$plaza','$consultaProducto'")->queryScalar();

        $totalInstaladasDiasHabiles = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '2','$tipoElemento','$uen','$tipoSolicitud','s','$plaza','$consultaProducto'")->queryScalar();
        $totalInstaladasDiasFinSemana = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '2','$tipoElemento','$uen','$tipoSolicitud','e','$plaza','$consultaProducto'")->queryScalar();
        $totalInstaladasDiasFestivos = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '2','$tipoElemento','$uen','$tipoSolicitud','f','$plaza','$consultaProducto'")->queryScalar();

        $totalDiasHabiles = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '3','','','','s','$plaza','$consultaProducto'")->queryAll();
        $totalDiasFinSemana = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '3','','','','e','$plaza','$consultaProducto'")->queryAll();
        $totalDiasFestivos = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '3','','','','f','$plaza','$consultaProducto'")->queryAll();

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
        $numeroDiaActual = date('j', strtotime(date('Y-m-d')));

        $numeroDiaActual;
        // Se muestra el presupuesto
        if ($numeroDiaActual <= 10) {
            $proyectadoCierre = new Presupuestos();
            $proyectadoCierre = $proyectadoCierre->get_Presupuesto($tipoElemento, $uen, date('Y'), date('n'), '', 1, $consultaProducto);
        }
        // TotalInstaladas en el mes + promedio instalado en dias habiles * dias habiles faltantes + promedio instaladas en dias festivos y fines de semana * Dias festivos faltantes + total de pendientes(restando 25% de estos).
        elseif ($numeroDiaActual >= 11 && $numeroDiaActual <= 26) {
            $ventas = new Ventas();
            $totalPendientes = $ventas->TotalPendientes($tipoElemento, $uen, $tipoSolicitud, $consultaProducto);
            $totalPendientes = ($totalPendientes - ($totalPendientes * 0.25));
            $proyectadoCierre = ($totalInstaladasMes + ($promedioInstaladasDiasHabiles * $numeroDiasHabilesFaltantes) + ($promedioInstaladasDiasFestivos * $numeroDiasFestivosFaltantes)) + $totalPendientes;
        } else {
            $proyectadoCierre = $totalInstaladasMes + ($promedioInstaladasDiasHabiles * $numeroDiasHabilesFaltantes) + ($promedioInstaladasDiasFestivos * $numeroDiasFestivosFaltantes);
        }

//      $proyectadoCierre = $totalInstaladasMes + ($promedioInstaladasDiasHabiles * $numeroDiasHabilesFaltantes) + ($promedioInstaladasDiasFestivos * $numeroDiasFestivosFaltantes);
        return $proyectadoCierre;
    }

    /**
     * Devuelve el numero de 
     * @param string $tipoElemento
     * @param string $uen
     * @param string $tipoSolicitud
     * @return string Con el numero de pedidos pendientes por gestionar
     */
    public function TotalPendientes($tipoElemento, $uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '') {
        $pendientes = Yii::app()->db->createCommand("SP_Total_Pendientes '$tipoElemento','$uen','$tipoSolicitud','$consultaProducto'")->queryScalar();
        return $pendientes;
    }

    /**
     *
     * Devuelve el numero de pedidos ingresados en el mes actual
     * @param type $tipoElemento
     * @param type $uen
     * @param type $plaza
     * @param type $tipoSolicitud
     * @param type $consultaProducto
     * @return string numero de ingresados
     */
    public function get_Instaladas_Canales_Mes($tipoElemento, $uen = '', $plaza = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '') {
        $ventasIngresadasMes = Yii::app()->db->createCommand("SP_Instaladas_Ingresadas_Canal '3','$tipoElemento','$uen','$plaza','$tipoSolicitud','$consultaProducto'")->queryAll();
        return $ventasIngresadasMes;
    }
}

