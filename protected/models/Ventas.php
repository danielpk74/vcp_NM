<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Ventas {

    /**
     * Devuelve todos los ingresos e instalaciones agrupados por plaza.
     * <br>ejecuta el SP: SP_Consultas_Ingresos_Retiros
     * @param type $dias
     * @param type $tipoElemento
     * @param type $plaza
     * @param type $fecha
     * @param type $uen
     * @param type $tipoSolicitud
     * @param type $consultaProducto
     * @param type $mes
     * @param type $regional
     * @param type $tipoCanal
     * @return type
     */
    public function Ingresadas($dias, $tipoElemento, $plaza = '', $fecha = '', $uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '', $mes = '', $regional = '', $anio = '', $tipoCanal = '') {
        $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '3','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipoSolicitud','$consultaProducto','$mes','$regional','$anio','$tipoCanal'")->queryAll();
        return $ventas;
    }

    /**
     * Consulta los ingresos y las instalaciones de las plazas que no se muestran por separado(Otros).
     * <br> ejecuta el SP: SP_Consultas_Ingresos_Retiros
     * @param integer $dias El numero de dias que se desea consultar desde la fecha hacia atras (ejem: los ultimos 7 dias), parametro requerido cuando se quiere generar grafico por dias
     * @param varchar $tipoElemento Tipo elemento a consultar (ejem: NUMMOV)
     * @param varchar $plaza La plaza que se desea consultar
     * @param date $fecha La fecha que se quiere consultar, parametro requerido cuando se desea ver los ingresos e instalaciones de una fecha especifica
     * @param varchar $uen La uen que se desea consultar (Pymes,Corporativos,Hogares)
     * @param varchar $tipoSolicitud Tipos de Solicitud (Nuevo,Retiro)
     * @param varchar $consultaProducto Tipos de Solicitud (Nuevo,Retiro)
     * @param integer $mes
     * @param integer $regional
     * * @param type $tipoCanal
     * @return array
     */
    public function IngresadasOtros($dias, $tipoElemento, $plaza = '', $fecha = '', $uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '', $mes = '', $regional = '', $anio = '', $tipoCanal = '') {
        $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '4','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipoSolicitud','$consultaProducto','$mes','$regional','$anio','$tipoCanal'")->queryAll();
        return $ventas;
    }

    /**
     * Obtiene el numero de pedidos ingresados por plaza agrupado por fecha, partiendo desde 
     * el numero de dias enviados por parametros, hasta la fecha actual.
     * <br> ejecuta el SP: SP_Consultas_Ingresos_Retiros
     * @param integer $dias el numero de dias desde que se debe traer el historial
     * @param string $tipoElemento Codigo con el que se encuentra en FENIX el producto de 4G O 3G, NUMMOV, LIMOV, TO, ETC
     * @param string $plaza
     * @param date $fecha
     * @param string $uen
     * @param string $tipoSolicitud
     * @param integer $consultaProducto
     * @param integer $mes
     * @param integer $regional
     * * @param type $tipoCanal
     * @return array con los datos de los ingresos agrupados por fecha
     */
    public function get_Ingresadas($dias, $tipoElemento, $plaza = '', $fecha = '', $uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '', $mes = '', $regional = '', $anio = '', $tipoCanal = '') {
        $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '1','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipoSolicitud','$consultaProducto','$mes','$regional','$anio','$tipoCanal'")->queryAll();
        return $ventas;
    }

    /**
     * Obtiene el numero de pedidos instalados por plaza agrupado por fecha, partiendo desde 
     * el numero de dias enviados por parametros, hasta la fecha actual.
     * <br>ejecuta el SP: SP_Consultas_Ingresos_Retiros
     * @param integer $dias el numero de dias desde que se debe traer el historial
     * @param type $tipoElemento
     * @param type $plaza
     * @param type $fecha
     * @param type $uen
     * @param type $tipoSolicitud
     * @param type $consultaProducto
     * @param type $mes
     * @param type $regional
     * * @param type $tipoCanal
     * @return array con los datos de los ingresos agrupados por fecha
     */
    public function get_Instaladas($dias, $tipoElemento, $plaza = '', $fecha = '', $uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '', $mes = '', $regional = '', $anio = '', $tipoCanal = '') {
        $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '2','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipoSolicitud','$consultaProducto','$mes','$regional','$anio','$tipoCanal'")->queryAll();
        return $ventas;
    }

    /**
     * Devuelve el numero de pedidos ingresados en el mes actual
     * @param type $tipoElemento
     * @param type $uen
     * @param type $tipoSolicitud
     * @param type $tipoDia
     * @param type $plaza
     * @param type $mes
     * @param type $consultaProducto
     * @return string numero de ingresados
     */
    public function get_TotalIngresadasMes($tipoElemento, $uen = '', $tipoSolicitud = 'Nuevo', $tipoDia = '', $plaza = '', $mes, $consultaProducto = '', $tipoCanal = '') {
        $ventasIngresadasMes = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '1','$tipoElemento','$uen','$tipoSolicitud','$tipoDia','$plaza','$mes','$consultaProducto','$tipoCanal'")->queryScalar();
        return $ventasIngresadasMes;
    }

    /**
     * Devuelve el numero de pedidos instalados en el mes actual
     * @return string numero de instalados
     */
    public function get_TotalInstaladasMes($tipoElemento, $uen = '', $tipoSolicitud = 'Nuevo', $tipoDia = '', $plaza = '', $mes, $consultaProducto = '', $tipoCanal = '') {
        $ventasInstaladasMes = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '2','$tipoElemento','$uen','$tipoSolicitud','$tipoDia','$plaza','$mes','$consultaProducto','$tipoCanal'")->queryScalar();
        return $ventasInstaladasMes;
    }

    /**
     * Calcula el numero de instalaciones aproximadas en las que cerrara el mes actual
     * tipo dia: e - fin de semana , s -  semana/dia habil, f - festivos
     * 
     * @param type $tipoElemento
     * @param type $uen
     * @param type $tipoSolicitud
     * @param type $tipoDia
     * @param type $plaza
     * @param type $consultaProducto
     * @return string
     */
    public static function get_ProyectadoMes($tipoElemento, $uen = '', $tipoSolicitud = '', $tipoDia = '', $plaza = '', $mes = '', $consultaProducto = '') {

        $numeroDiaActual = date('j', strtotime(date('Y-m-d')));
        
        $mes = '';
        if ($numeroDiaActual <= 8) 
            $mes = (date('n', strtotime(date('Y-m-d')))) - 1;
        else
            $mes = (date('n', strtotime(date('Y-m-d')))); 

        $totalInstaladasMes = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '2','$tipoElemento','$uen','$tipoSolicitud','','$plaza','$mes','$consultaProducto'")->queryScalar();

        $totalInstaladasDiasHabiles = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '2','$tipoElemento','$uen','$tipoSolicitud','s','$plaza','$mes','$consultaProducto'")->queryScalar();
        $totalInstaladasDiasFinSemana = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '2','$tipoElemento','$uen','$tipoSolicitud','e','$plaza','$mes','$consultaProducto'")->queryScalar();
        $totalInstaladasDiasFestivos = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '2','$tipoElemento','$uen','$tipoSolicitud','f','$plaza','$mes','$consultaProducto'")->queryScalar();

        $totalDiasHabiles = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '3','','','','s','$plaza','$mes','$consultaProducto'")->queryAll();
        $totalDiasFinSemana = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '3','','','','e','$plaza','$mes','$consultaProducto'")->queryAll();
        $totalDiasFestivos = Yii::app()->db->createCommand("SP_Ingresadas_Instaladas_X_Mes '3','','','','f','$plaza','$mes','$consultaProducto'")->queryAll();

        // Se suman los festivos y los fines de semana por sugerencia de Mauricio Cano, "el comportamiento es muy similar"
        $totalInstaladasDiasFinSemana = $totalInstaladasDiasFinSemana + $totalInstaladasDiasFestivos;
        $totalDiasFinSemana = array_merge($totalDiasFinSemana,$totalDiasFestivos);
       
        // Promedio de instalaciones en dias habiles
        if (Count($totalDiasHabiles) != 0)
            $promedioInstaladasDiasHabiles = $totalInstaladasDiasHabiles / Count($totalDiasHabiles);

        // Promedio de instalaciones en dias fin de semana
        if (Count($totalDiasFinSemana) != 0)
            $promedioInstaladasDiasFestivos = $totalInstaladasDiasFinSemana / Count($totalDiasFinSemana);

        $numeroDiasHabilesFaltantes = FunsionesSoporte::get_DiasFaltantesMes(1);
        $numeroDiasFestivosFaltantes = FunsionesSoporte::get_DiasFaltantesMes(2);

        // Se muestra el presupuesto
        if ($numeroDiaActual <= 8) {
            $ventas = new Ventas();
            $totalPendientes = $ventas->TotalPendientes($tipoElemento, $uen, $tipoSolicitud, $consultaProducto, $plaza, $regional);
            $totalPendientes = ($totalPendientes - ($totalPendientes * 0.20));
            $proyectadoCierre = (($promedioInstaladasDiasHabiles * $numeroDiasHabilesFaltantes) + ($promedioInstaladasDiasFestivos * $numeroDiasFestivosFaltantes)) + $totalPendientes;
        }

        // TotalInstaladas en el mes + promedio instalado en dias habiles * dias habiles faltantes + promedio instaladas en dias festivos y fines de semana * Dias festivos faltantes + total de pendientes(restando 25% de estos).
        elseif ($numeroDiaActual >= 9 && $numeroDiaActual <= 25) {
            $ventas = new Ventas();
            $totalPendientes = $ventas->TotalPendientes($tipoElemento, $uen, $tipoSolicitud, $consultaProducto, $plaza, $regional);
            $totalPendientes = ($totalPendientes - ($totalPendientes * 0.20));
            $proyectadoCierre = ($totalInstaladasMes + ($promedioInstaladasDiasHabiles * $numeroDiasHabilesFaltantes) + ($promedioInstaladasDiasFestivos * $numeroDiasFestivosFaltantes)) + $totalPendientes;
//            echo $totalInstaladasMes . " - " . ($promedioInstaladasDiasHabiles * $numeroDiasHabilesFaltantes) . " - " . ($promedioInstaladasDiasFestivos * $numeroDiasFestivosFaltantes);
        } elseif ($numeroDiaActual >= 26 && $numeroDiaActual <= 27) {
            $ventas = new Ventas();
            $totalPendientes = $ventas->TotalPendientes($tipoElemento, $uen, $tipoSolicitud, $consultaProducto, $plaza, $regional, '', '');
            $totalPendientes = ($totalPendientes - ($totalPendientes * 0.70));
            $proyectadoCierre = ($totalInstaladasMes + ($promedioInstaladasDiasHabiles * $numeroDiasHabilesFaltantes) + ($promedioInstaladasDiasFestivos * $numeroDiasFestivosFaltantes)) + $totalPendientes;
        } elseif ($numeroDiaActual >= 28 && $numeroDiaActual <= 29) {
            $ventas = new Ventas();
            $totalPendientes = $ventas->TotalPendientes($tipoElemento, $uen, $tipoSolicitud, $consultaProducto, $plaza, $regional, '', '');
            $totalPendientes = ($totalPendientes - ($totalPendientes * 0.80));
            $proyectadoCierre = ($totalInstaladasMes + ($promedioInstaladasDiasHabiles * $numeroDiasHabilesFaltantes) + ($promedioInstaladasDiasFestivos * $numeroDiasFestivosFaltantes)) + $totalPendientes;
        } elseif ($numeroDiaActual >= 30 && $numeroDiaActual <= 31) {
            $ventas = new Ventas();
            $totalPendientes = $ventas->TotalPendientes($tipoElemento, $uen, $tipoSolicitud, $consultaProducto, $plaza, $regional, '', '');
            $totalPendientes = ($totalPendientes - ($totalPendientes * 0.90));
            $proyectadoCierre = ($totalInstaladasMes + ($promedioInstaladasDiasHabiles * $numeroDiasHabilesFaltantes) + ($promedioInstaladasDiasFestivos * $numeroDiasFestivosFaltantes)) + $totalPendientes;
        } else {
            $proyectadoCierre = $totalInstaladasMes + ($promedioInstaladasDiasHabiles * $numeroDiasHabilesFaltantes) + ($promedioInstaladasDiasFestivos * $numeroDiasFestivosFaltantes);
        }

        return $proyectadoCierre;
    }

    /**
     * Devuelve el numero de ventas pendientes de instalacion 
     * @param type $tipoElemento
     * @param type $uen
     * @param type $tipoSolicitud
     * @param type $consultaProducto
     * @param type $plaza
     * @param type $regional
     * @param type $anio
     * @param type $tipoCanal
     * @return string Con el numero de pedidos pendientes por gestionar
     */
    public function TotalPendientes($tipoElemento, $uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '', $plaza = '', $regional = '', $anio = '', $tipoCanal = '') {
        $pendientes = Yii::app()->db->createCommand("SP_Total_Pendientes '1','$tipoElemento','$uen','$tipoSolicitud','$consultaProducto','$plaza','$regional','$anio','$tipoCanal'")->queryScalar();
        return $pendientes;
    }

    /**
     * Devuelve el numero de ventas pendientes de instalacion agrupado por mes
     * @param type $tipoElemento
     * @param type $uen
     * @param type $tipoSolicitud
     * @param type $consultaProducto
     * @param type $plaza
     * @param type $regional
     * @return type
     */
    public function TotalPendientes_X_Mes($tipoElemento, $uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '', $plaza = '', $regional = '', $anio = '', $tipoCanal = '') {
        $pendientes = Yii::app()->db->createCommand("SP_Total_Pendientes '2','$tipoElemento','$uen','$tipoSolicitud','$consultaProducto','$plaza','$regional','$anio','$tipoCanal'")->queryAll();
        return $pendientes;
    }

    /**
     * Devuelve el numero de pedidos ingresados en el mes actual
     * @param type $tipoElemento
     * @param type $uen
     * @param type $plaza
     * @param type $tipoSolicitud
     * @param type $consultaProducto
     * @return string numero de ingresados
     */
    public function get_Instaladas_Canales_Mes($tipoElemento, $uen = '', $plaza = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '', $fechaConsulta = '') {
        $ventasIngresadasMes = Yii::app()->db->createCommand("SP_Instaladas_Ingresadas_Canal '3','$tipoElemento','$uen','$plaza','$tipoSolicitud','$consultaProducto','$fechaConsulta'")->queryAll();
        return $ventasIngresadasMes;
    }

    /**
     * Obtiene el numero de pedidos instalados por plaza agrupado por fecha, partiendo desde 
     * el numero de dias enviados por parametros, hasta la fecha actual.
     * <br> ejecuta el SP: SP_Consultas_Ingresos_Retiros
     * @param type $dias
     * @param type $tipoElemento
     * @param type $plaza
     * @param type $fecha
     * @param type $uen
     * @param type $tipoSolicitud
     * @param type $consultaProducto
     * @param type $mes
     * @param type $regional
     * @param type $anio
     * @param type $tipoCanal
     * @return array con los datos de los ingresos agrupados por fecha
     */
    public function get_InstaladasTotales_X_Mes($dias, $tipoElemento, $plaza = '', $fecha = '', $uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '', $mes = '', $regional = '', $anio = '', $tipoCanal = '') {
        $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '5','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipoSolicitud','$consultaProducto','$mes','$regional','$anio','$tipoCanal'")->queryAll();
        return $ventas;
    }

    /**
     * Obtiene el numero de pedidos ingresadas por meses
     * ejecuta el SP: SP_Consultas_Ingresos_Retiros
     * @param type $dias
     * @param type $tipoElemento
     * @param type $plaza
     * @param type $fecha
     * @param type $uen
     * @param type $tipoSolicitud
     * @param type $consultaProducto
     * @param type $mes
     * @param type $regional
     * @param type $anio
     * @param type $tipoCanal
     * @return array con los datos de los ingresos agrupados por fecha
     */
    public function get_IngresadasTotales_X_Mes($dias, $tipoElemento, $plaza = '', $fecha = '', $uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '', $mes = '', $regional = '', $anio = '', $tipoCanal = '') {
        $ventas = Yii::app()->db->createCommand("SP_Consultas_Ingresos_Retiros '6','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipoSolicitud','$consultaProducto','$mes','$regional','$anio','$tipoCanal'")->queryAll();
        return $ventas;
    }

    /**
     * Obtiene el numero de pedidos ingresadas por meses
     * @param type $dias
     * @param type $tipoElemento
     * @param type $plaza
     * @param type $fecha
     * @param type $uen
     * @param type $tipoSolicitud
     * @param type $consultaProducto
     * @param type $mes
     * @param type $regional
     * @param type $anio
     * @param type $tipoCanal
     * @return type
     */
    public function get_Anuladas_X_Mes($dias, $tipoElemento, $plaza = '', $fecha = '', $uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '', $mes = '', $regional = '', $anio = '', $tipoCanal = '') {
        $ventas = Yii::app()->db->createCommand("SP_Consultas_Anuladas '1','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipoSolicitud','$consultaProducto','$mes','$regional','$anio','$tipoCanal'")->queryAll();
        return $ventas;
    }

    /**
     * Obtiene el numero de pedidos ingresadas por meses <br>
     * Utiliza el SP_Consultas_Regional
     * @param type $dias
     * @param type $tipoElemento
     * @param type $plaza
     * @param type $fecha
     * @param type $uen
     * @param type $tipoSolicitud
     * @param type $consultaProducto
     * @param type $anio
     * @param type $tipoCanal
     * @return array con los datos de los ingresos agrupados por fecha
     */
    public function get_IngresadasTotales_X_Mes_X_Regional($dias, $tipoElemento, $plaza = '', $fecha = '', $uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '', $mes = '', $anio = '', $tipoCanal = '') {
        if ($dias != '')
            $ventas = Yii::app()->db->createCommand("SP_Consultas_Regional '1','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipoSolicitud','$consultaProducto','$mes','$anio','$tipoCanal'")->queryAll();
        else
            $ventas = Yii::app()->db->createCommand("SP_Consultas_Regional '3','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipoSolicitud','$consultaProducto','$mes','$anio','$tipoCanal'")->queryAll();

        return $ventas;
    }

    /**
     * Obtiene el numero de pedidos ingresadas por meses
     * @param type $dias
     * @param type $tipoElemento
     * @param type $plaza
     * @param type $fecha
     * @param type $uen
     * @param type $tipoSolicitud
     * @param type $consultaProducto
     * @param type $anio
     * @param type $tipoCanal
      @return array con los datos de los ingresos agrupados por fecha
     */
    public function get_InstaladasTotales_X_Mes_X_Regional($dias, $tipoElemento, $plaza = '', $fecha = '', $uen = '', $tipoSolicitud = 'Nuevo', $consultaProducto = '', $anio = '', $tipoCanal = '') {
        if ($dias != '')
            $ventas = Yii::app()->db->createCommand("SP_Consultas_Regional '2','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipoSolicitud','$consultaProducto','$anio','$tipoCanal'")->queryAll();
        else
            $ventas = Yii::app()->db->createCommand("SP_Consultas_Regional '4','$dias','$tipoElemento','$plaza','$fecha','$uen','$tipoSolicitud','$consultaProducto','$anio','$tipoCanal'")->queryAll();
        return $ventas;
    }

}

