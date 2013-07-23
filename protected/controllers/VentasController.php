<?php

class VentasController extends Controller {

    public static function Ventas($vista) {
        $fechaActualizacion = Configuracion::get_FechaActualizacion();

        $subProducto = new SubProductos();

        // Producto a consultar por defecto
        $subProductoQuery = "";

        $tipoElemento = "";
        $plaza = "";
        $fecha = "";
        $uenc = "";
        $tipo_solicitud = 'Nuevo';
        $numeroDias = 15;
        $consultaProducto = '1'; // Determina si va a consultar un producto o un subproducto, esto para definir un filtro en el SP
        // ACCION FILTRAR
        // Si la peticion viene por via ajax
        if (Yii::app()->request->isAjaxRequest) {
            if (Yii::app()->getRequest()->getParam('uen') != "") {
                $uenc = Yii::app()->getRequest()->getParam('uen');
                $uenc = implode(",", $uenc);
            }

            // Si filtra por tipo canal
            if (Yii::app()->getRequest()->getParam('tipoCanal') != "")
                $tipoCanal = implode(",", Yii::app()->getRequest()->getParam('tipoCanal'));

            if (Yii::app()->getRequest()->getParam('fecha') != "")
                $fecha = Yii::app()->getRequest()->getParam('fecha');

            // Numero de los ultimos dias a consultar, 7, 15, 30
            $numeroDias = Yii::app()->getRequest()->getParam('periodo');

            if (Yii::app()->getRequest()->getParam('subproducto') == "" && Yii::app()->getRequest()->getParam('producto') == "") {
                // Si no se realiza un filtro de ningun producto automaticamente tomara 4G
                $vista->setPageState('producto', '4G,4G FIJO');
            } else {
                // Si consulta solo por producto
                if (Yii::app()->getRequest()->getParam('subproducto') == "") {
                    $vista->setPageState('producto', implode(",", Yii::app()->getRequest()->getParam('producto')));
                }
                // Consulta por subproducto
                else {
                    $vista->setPageState('producto', implode(",", Yii::app()->getRequest()->getParam('subproducto')));
                    $consultaProducto = '';
                }
            }
        }

        // Si no es una peticion ajax, consultara automaticamente 4G
        else {

            Usuarios::registrarUsuario($_SERVER["REMOTE_ADDR"], date('Y-m-d H:i:s'), 1);

            $subProductos = $subProducto->get_SubProductos('');
            $vista->setPageState('producto', '4G,4G FIJO');

            $tiposCanales = new TipoCanal();
            $tiposCanales = $tiposCanales->get_Tipo_Canal_Todos();

            $uen = new Uen();
            $uens = $uen->get_UEN_Todas();

            $producto_ = new Productos();
            $productos = $producto_->get_Productos();
        }

        $ventas = new Ventas();

        /// Total Ingresadas e Instaladas por Mes
        $mes = date('n', strtotime(date('Y-m-d')));
        if ($fecha != '') {
            $mes = strlen($fecha == 2) ? $fecha : str_replace("0", "", $fecha);
            $fecha = date('Y') . "-" . $fecha . "-" . FunsionesSoporte::get_Ultimo_dia_Mes($mes, date('Y'));
        }

        $totalIngresadasMesActual = $ventas->get_TotalIngresadasMes($vista->getPageState('producto'), $uenc, 'Nuevo', '', '', $mes, $consultaProducto, $tipoCanal);
        $totalInstaladasMesActual = $ventas->get_TotalInstaladasMes($vista->getPageState('producto'), $uenc, 'Nuevo', '', '', $mes, $consultaProducto, $tipoCanal);
        $totalPendientes = $ventas->TotalPendientes($vista->getPageState('producto'), $uenc, 'Nuevo', $consultaProducto, '', '', '', $tipoCanal);

        /// Total Ingresadas e Instaladas por dia - Para el grafico
        if ($fecha == '') { // Si no se esta consultando alguna fecha especifica, tomara los dias seleccionados en el periodo, por defecto 15 dias
            $ventasIngresadas = $ventas->get_Ingresadas($numeroDias, $vista->getPageState('producto'), '', '', $uenc, 'Nuevo', $consultaProducto, $mes, '', '', $tipoCanal);
            $ventasInstaladas = $ventas->get_Instaladas($numeroDias, $vista->getPageState('producto'), '', '', $uenc, 'Nuevo', $consultaProducto, $mes, '', '', $tipoCanal);
            $ventasIngresadas = FunsionesSoporte::CompletarDias($ventasIngresadas, 2, $numeroDias);
            $ventasInstaladas = FunsionesSoporte::CompletarDias($ventasInstaladas, 1, $numeroDias);
        } else {
            $ventasIngresadas = $ventas->get_Ingresadas($fecha, $vista->getPageState('producto'), '', '', $uenc, 'Nuevo', $consultaProducto, $mes, '', '', $tipoCanal);
            $ventasInstaladas = $ventas->get_Instaladas($fecha, $vista->getPageState('producto'), '', '', $uenc, 'Nuevo', $consultaProducto, $mes, '', '', $tipoCanal);
        }

        $proyectadoCierre = $ventas->get_ProyectadoMes($vista->getPageState('producto'), $uenc, 'Nuevo', '', '', '', $consultaProducto);

        // Consulta inicial/Consulta de evolucion diaria.
        if ($fecha == '') {
            // --- Define si muestra los datos del dia anterior o del dia en curso
            // Dia anterior


            if (date('A', strtotime($fechaActualizacion)) == 'AM' || date('Y-m-d', strtotime($fechaActualizacion)) != date('Y-m-d')) {
                $fechaConsulta = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d'))));
                $diaConsulta = "AYER";
            }
            // Dia en Curso
            else {
                $fechaConsulta = date('Y-m-d');
                $diaConsulta = "HOY";
            }
        } else { // Consulta por mes
            $fechaConsulta = $fecha;
            $diaConsulta = "";
        }

        ///// Para las Ingresadas e instaladas del dia anterior
        /// Ingresadas/Instaladas de plazas principales
        $ingresadas_instaladas = $ventas->Ingresadas('', $vista->getPageState('producto'), '', $fechaConsulta, $uenc, 'Nuevo', $consultaProducto, $mes, '', '', $tipoCanal);
        foreach ($ingresadas_instaladas as $v) {

            $cumplimiento = 0;

            // Presupuesto por plaza
            $presupuesto = round(FunsionesSoporte::get_Presupuesto_X_Plaza($v['PLAZA'], $uenc, $vista->getPageState('producto'), date('Y'), $mes, '', $consultaProducto));

            // Presupuesto de las ciudades que no aparecen como plazas
            $presupuestoOtros = round(FunsionesSoporte::get_Presupuesto_X_Plaza('', $uenc, $vista->getPageState('producto'), date('Y'), $mes, '', $consultaProducto));

            // Total de instaladas por plaza por mes
            $totalInstaladasPlaza = $ventas->get_TotalInstaladasMes($vista->getPageState('producto'), $uenc, 'Nuevo', '', $v['PLAZA'], $mes, $consultaProducto, $tipoCanal);

            if ($presupuesto != 0)
                $cumplimiento = ($totalInstaladasPlaza / $presupuesto) * 100;

            // Para totalizar el presupuesto de las plazas, los primeros 10 dias debe ser igual al proyectado para el mes
            $totalPresupuestoPlazas += $presupuesto;

            $ventasTotales[] = array('PLAZA' => $v['PLAZA'],
                'INGRESADAS' => $v['INGRESADAS'],
                'INSTALADAS' => $v['INSTALADAS'],
                'TOTAL_PLAZA' => number_format($totalInstaladasPlaza, '0', ',', '.'),
                'PRESUPUESTO' => number_format($presupuesto, '0', ',', '.'),
                'CUMPLIMIENTO' => number_format($cumplimiento, '0', ',', '.'));
        }

        ///---------COMIENZA LA CONSULTA DE LOS DATOS DE LAS CIUDADES QUE NO SON MOSTRADAS COMO PLAZAS.(OTROS)
        // Ingresadas/Instaladas de las ciudades que no aparecen como plazas
        $ingresadasInstaladasTotalesOtros = $ventas->IngresadasOtros('', $vista->getPageState('producto'), '', $fechaConsulta, $uenc, 'Nuevo', $consultaProducto, $mes, '', '', $tipoCanal);
        $totalInstaladas = 0;
        $totalIngresadas = 0;

        foreach ($ingresadasInstaladasTotalesOtros as $ventasOtros) {
            $totalInstaladas += $ventasOtros['INSTALADAS'];
            $totalIngresadas += $ventasOtros['INGRESADAS'];
        }

        // Total instaladas en el mes para las ciudades que no aparecen como plaza
        $instaladasTotalesOtrosMes = $ventas->IngresadasOtros('', $vista->getPageState('producto'), '', '', $uenc, 'Nuevo', $consultaProducto, $mes, '', '', $tipoCanal);
        foreach ($instaladasTotalesOtrosMes as $ventasOtros) {
            $totalInstaladasOtrosMes += $ventasOtros['INSTALADAS'];
        }

        // Para totalizar el presupuesto de las ciudades que no aparecen como plaza, los primeros 10 dias debe ser igual al proyectado para el mes
        $totalPresupuestoPlazas += $presupuestoOtros;

        if ($presupuestoOtros != 0)
            $cumplimiento = ($totalInstaladasOtrosMes / $presupuestoOtros) * 100;

        $TotalesOtros[] = array('PLAZA' => 'Otros', 'INGRESADAS' => $totalIngresadas, 'INSTALADAS' => $totalInstaladas, 'TOTAL_PLAZA' => $totalInstaladasOtrosMes, 'PRESUPUESTO' => number_format($presupuestoOtros, '0', ',', '.'), 'CUMPLIMIENTO' => number_format($cumplimiento, '0', ',', '.'));

        ///----- Ingresadas/Instaladas de otras plazas --------///
        if ($numeroDias == "")
            $numeroDias = "Mes Actual";
        else {
            if (Yii::app()->getRequest()->getParam('fecha') != '')
                $numeroDias = FunsionesSoporte::get_NombreMes($fecha);
            else
                $numeroDias = "Últimos $numeroDias días Hasta el " . date('d-m-Y h:i', strtotime($fechaActualizacion));
        }

        if ($totalPresupuestoPlazas != 0)
            $totalCumplimiento = ($totalInstaladasMesActual / $totalPresupuestoPlazas) * 100;

        // Si se filtra por una fecha especificada el proyectado sera igual al numero de instalaciones de ese mes
        if ($fecha != '')
            $proyectadoCierre = $totalInstaladasMesActual;
        ///---------FIN DE LA CONSULTA DE LOS DATOS DE LAS CIUDADES QUE NO SON MOSTRADAS COMO PLAZAS.(OTROS)




        $arrayDatos = array(
            'fechaactualizacion' => $fechaActualizacion,
            'fechaConsulta' => $fechaConsulta,
            'producto' => $vista->getPageState('producto'), // Producto Consultado
            'productomodel' => $producto_, // Modelo de producto
            'subProducto_' => $subProducto, // Subproducto Consultado
            'productos' => $productos, // Todos los productos
            'subProductos' => $subProductos, // Todos los subproductos
            'ventas' => $ventasTotales,
            'ventasOtros' => $TotalesOtros,
            'ventasIngresadas' => $ventasIngresadas,
            'ingresadasMesActual' => number_format($totalIngresadasMesActual, '0', ',', '.'),
            'instaladasMesActual' => number_format($totalInstaladasMesActual, '0', ',', '.'),
            'totalPendientes' => number_format($totalPendientes, '0', ',', '.'),
            'proyectadoMesActual' => number_format($proyectadoCierre, '0', ',', '.'),
            'totalCumplimiento' => number_format($totalCumplimiento, '0', ',', '.'),
            'uens' => $uens, // Todas las uens
            'uenc' => $uenc, // La uen que se consulta
            'uenmodel' => $uen, // El Modelo de uen
            'ventasInstaladas' => $ventasInstaladas,
            'diaConsulta' => $diaConsulta,
            'numeroDiasConsulta' => $numeroDias,
            'fecha' => $fecha,
            'presupuestoTotalPlaza' => number_format($totalPresupuestoPlazas, '0', ',', '.'),
            'tipocanalmodel' => new TipoCanal(),
            'tiposCanales' => $tiposCanales,
            'consultaProducto' => $consultaProducto
        );

        return $arrayDatos;
    }

    public static function VentasGeneral($vista) {

        $uenc = "";
        $regional = "";
        $tipoCanal = "";
        $plaza = "";
        $anio = date('Y');


        $consultaProducto = '1'; // Determina si va a consultar un producto o un subproducto, esto para definir un filtro en el SP
        // $mes = date('n');
        ///  Filtros -------------------------------------------------------------------------------------
        if (Yii::app()->request->isAjaxRequest) {
            if (Yii::app()->getRequest()->getParam('uen') != "") {
                $uenc = Yii::app()->getRequest()->getParam('uen');
                $uenc = implode(",", $uenc);
            }

            // Si filtra por regional
            if (Yii::app()->getRequest()->getParam('regional') != "")
                $regional = implode(",", Yii::app()->getRequest()->getParam('regional'));

            // Si filtra por año
            if (Yii::app()->getRequest()->getParam('anio') != "")
                $anio = Yii::app()->getRequest()->getParam('anio');

            // Si filtra por tipo canal
            if (Yii::app()->getRequest()->getParam('tipoCanal') != "")
                $tipoCanal = implode(",", Yii::app()->getRequest()->getParam('tipoCanal'));

            // Si filtra por plaza
            if (Yii::app()->getRequest()->getParam('plaza') != "") {
                $plaza = implode(",", Yii::app()->getRequest()->getParam('plaza'));
            }

            if (Yii::app()->getRequest()->getParam('subproducto') == "" && Yii::app()->getRequest()->getParam('producto') == "") {
                // Si no se realiza un filtro de ningun producto automaticamente tomara 4G
                $vista->setPageState('producto', '4G,4G FIJO');
            } else {
                // Si consulta solo por producto
                if (Yii::app()->getRequest()->getParam('subproducto') == "") {
                    $vista->setPageState('producto', implode(",", Yii::app()->getRequest()->getParam('producto')));
                }
                // Consulta por subproducto
                else {
                    $vista->setPageState('producto', implode(",", Yii::app()->getRequest()->getParam('subproducto')));
                    $consultaProducto = '';
                }
            }
        }

        // Si no es una peticion ajax, consultara automaticamente 4G
        else {
            Usuarios::registrarUsuario($_SERVER["REMOTE_ADDR"], date('Y-m-d H:i:s'), 2);

            $productos = new Productos();
            $productos = $productos->get_Productos();

            $tiposCanales = new TipoCanal();
            $tiposCanales = $tiposCanales->get_Tipo_Canal_Todos();

            $regionales = new Regionales();
            $regionales = $regionales->get_Regionales();

            $subProducto = new SubProductos();
            $subProductos = $subProducto->get_SubProductos('');

            $vista->setPageState('producto', '4G,4G FIJO');

            $plazas = new Plazas();
            $plazas = PlazasController::get_PlazasSinTilde($plazas->get_Plazas());

            $uen = new Uen();
            $uens = $uen->get_UEN_Todas();
        }
        // Terminan Filtros--------------------------------------------------------------------------
        $opcion = "Ingresos ";

        // Presupuesto para la grafica de ventas
        $presupuesto = new Presupuestos();
        $presupuestoMeses = $presupuesto->get_Presupuesto($vista->getPageState('producto'), $uenc, $anio, '', $plaza, 2, $consultaProducto, $regional, $tipoCanal);

        // Ingresos y retiros,--------------------------------------------------------------------------------------------------------------------------------------
        $ventas = new Ventas();
        $instaladas = $ventas->get_InstaladasTotales_X_Mes('', $vista->getPageState('producto'), $plaza, '', $uenc, 'Nuevo', $consultaProducto, $mes, $regional, $anio, $tipoCanal);
        $ingresadas = $ventas->get_IngresadasTotales_X_Mes('', $vista->getPageState('producto'), $plaza, '', $uenc, 'Nuevo', $consultaProducto, $mes, $regional, $anio, $tipoCanal);
        $anuladas = $ventas->get_Anuladas_X_Mes('', $vista->getPageState('producto'), $plaza, '', $uenc, 'Nuevo', $consultaProducto, '', $regional, $anio, $tipoCanal);
        $pendientes = $ventas->TotalPendientes_X_Mes($vista->getPageState('producto'), $uenc, 'Nuevo', $consultaProducto, $plaza, $regional, $anio, $tipoCanal);
        // --------------------------------------------------------------------------------------------------------------------------------------            

        $meses = FunsionesSoporte::get_NombreMes('', true);

        // Si los registros no comienzan en el mes de enero.
        $ingresadas = FunsionesSoporte::get_CompletarMesesAtras($ingresadas);
        $instaladas = FunsionesSoporte::get_CompletarMesesAtras($instaladas);
        $anuladas = FunsionesSoporte::get_CompletarMesesAtras($anuladas);
        $pendientes = FunsionesSoporte::get_CompletarMesesAtras($pendientes);
        $presupuestoMeses = FunsionesSoporte::get_CompletarMesesAtras($presupuestoMeses);

        // Si hay meses sin registros.
        $total = $ingresadas > $instaladas ? $ingresadas : $instaladas;
        $ingresadas = FunsionesSoporte::get_CompletarMesesIntermedios($total, $ingresadas, 'MES', $anio);
        $instaladas = FunsionesSoporte::get_CompletarMesesIntermedios($total, $instaladas, 'MES', $anio);
        $anuladas = FunsionesSoporte::get_CompletarMesesIntermedios($total, $anuladas, 'MES', $anio);
        $pendientes = FunsionesSoporte::get_CompletarMesesIntermedios($total, $pendientes, 'MES', $anio);
        $presupuestoMeses = FunsionesSoporte::get_CompletarMesesIntermedios($total, $presupuestoMeses, 'MES', '');

        // Fin termina el complemento de los meses para la primera grafica---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        //Total por Regional x Dias para el grafico de columnas combinadas ---------------------------------------------------------------------------------------------------------------------------------------------
        // Solo se genera el grafico combinado cuando se consulta el año actual.
        //COMIENZA PARA LAS COLUMNAS COMBINADAS
        //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------
        // Total por Regional x Mes para el grafico de columnas combinadas -------------------------------------------------------------------------------------------------------------------------------------------
        $ingresadasRegionalxMes = $ventas->get_IngresadasTotales_X_Mes_X_Regional('', $vista->getPageState('producto'), '', '', $uenc, 'Nuevo', $consultaProducto, $mes, $anio, $tipoCanal);
        $instaladasRegionalxMes = $ventas->get_InstaladasTotales_X_Mes_X_Regional('', $vista->getPageState('producto'), '', '', $uenc, 'Nuevo', $consultaProducto, $mes, $anio, $tipoCanal);
        //----------------------------------------------------------------------------------------------------------------------------------------------------------------------
        // Generamos las fechas de la categoria del grafico - este va por meses (Actualmente meses del año en curso)    

        if ($anio == date('Y')) {  // Solo generamos las columnas combinadas cuando se consulta el año actual
            $ingresadasRegional = $ventas->get_IngresadasTotales_X_Mes_X_Regional(7, $vista->getPageState('producto'), '', '', $uenc, 'Nuevo', $consultaProducto, $mes, $anio, $tipoCanal);
            $instaladasRegional = $ventas->get_InstaladasTotales_X_Mes_X_Regional(7, $vista->getPageState('producto'), '', '', $uenc, 'Nuevo', $consultaProducto, $mes, $anio, $tipoCanal);


            // Grafico por Regional x dias
            $fechaIngreso = "";
            foreach ($ingresadasRegional as $ventas) {
                if ($fechaIngreso != $ventas['FECHA_INGRESO']) {
                    $fechaIngreso = $ventas['FECHA_INGRESO'];
                    $arrayFechasIngresos[] = array('FECHA_INGRESO' => $fechaIngreso);
                }
            }

            // Generamos las fechas de la categoria del grafico - este va por meses (Actualmente meses del año en curso)
            // Grafico por Regional
            $fechaInstalacion = "";
            foreach ($instaladasRegional as $ventas) {
                if ($fechaInstalacion != $ventas['FECHA_INSTALACION']) {
                    $fechaInstalacion = $ventas['FECHA_INSTALACION'];
                    $arrayFechasInstalaciones[] = array('FECHA_INSTALACION' => $fechaInstalacion);
                }
            }

            // Genera el array requerido para el tipo de grafico utilizado
            $ingresadasRegional = RegionalesController::get_Ventas_CombinedColumn($ingresadasRegional, 'FECHA_INGRESO', false, $anio);
            $instaladasRegional = RegionalesController::get_Ventas_CombinedColumn($instaladasRegional, 'FECHA_INSTALACION', false, $anio);
        }
        /// Fin generar arrar de columnas combinadas por dias.
        // Generamos las fechas de la categoria del grafico - este va por meses (Actualmente meses del año en curso)
        // Grafico por Regional
        $fechaIngresoxMes = "";
        foreach ($ingresadasRegionalxMes as $ventas) {
            if ($fechaIngresoxMes != $ventas['FECHA_INGRESO']) {
                $fechaIngresoxMes = $ventas['FECHA_INGRESO'];
                $arrayFechasIngresosxMes[] = array('FECHA_INGRESO' => FunsionesSoporte::get_NombreMes($fechaIngresoxMes));
            }
        }

        // Generamos las fechas de la categoria del grafico - este va por meses (Actualmente meses del año en curso)
        // Grafico por Regional
        $fechaInstalacionxMes = "";
        foreach ($instaladasRegionalxMes as $ventas) {
            if ($fechaInstalacionxMes != $ventas['FECHA_INSTALACION']) {
                $fechaInstalacionxMes = $ventas['FECHA_INSTALACION'];
                $arrayFechasInstalacionesxMes[] = array('FECHA_INSTALACION' => FunsionesSoporte::get_NombreMes($fechaInstalacionxMes));
            }
        }

        $ingresadasRegionalxMes = RegionalesController::get_Ventas_CombinedColumn($ingresadasRegionalxMes, 'FECHA_INGRESO', true, $anio);
        $instaladasRegionalxMes = RegionalesController::get_Ventas_CombinedColumn($instaladasRegionalxMes, 'FECHA_INSTALACION', true, $anio);


//            var_dump($instaladasRegionalxMes);
//             var_dump($instaladasRegionalxMes);

        $arrayDatos = array(
            'regionales' => $regionales, // Producto Consultado
            'regionalesmodel' => new Regionales(), // Modelo de producto
            'producto' => $vista->getPageState('producto'), // Producto Consultado
            'productomodel' => new Productos(), // Modelo de producto
            'subProducto_' => new SubProductos(), // Subproducto Consultado
            'productos' => $productos, // Todos los productos
            'subProductos' => $subProductos, // Todos los subproductos
            'uenmodel' => new Uen(), // El Modelo de uen
            'uens' => $uens, // Todas las uens
            'plazasmodel' => new Plazas(), // Todas las uens
            'plazas' => $plazas, // Todas las uens
            'meses' => $meses,
            'presupuesto' => $presupuestoMeses,
            'instaladas' => $instaladas,
            'ingresadas' => $ingresadas,
            'anuladas' => $anuladas,
            'pendientes' => $pendientes,
            'fechasIngresos' => $arrayFechasIngresos,
            'ingresadasRegional' => $ingresadasRegional,
            'fechasInstalaciones' => $arrayFechasInstalaciones,
            'instaladasRegional' => $instaladasRegional,
            'fechasIngresosxMes' => $arrayFechasIngresosxMes,
            'ingresadasRegionalxMes' => $ingresadasRegionalxMes,
            'fechasInstalacionesxMes' => $arrayFechasInstalacionesxMes,
            'instaladasRegionalxMes' => $instaladasRegionalxMes,
            'tipocanalmodel' => new TipoCanal(),
            'tiposCanales' => $tiposCanales,
            'opcion' => $opcion);
    }

}