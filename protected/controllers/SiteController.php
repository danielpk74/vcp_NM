<?php

class SiteController extends Controller {

    /**
     * Carga al dropdownlist los subproductos pertenecientes a un producto enviado por parametro
     */
    public function actionCargarSubProductos() {
        if (Yii::app()->request->isAjaxRequest) {
            $productoID = Yii::app()->getRequest()->getParam('producto');
            $subProducto = new SubProductos();

            if ($productoID != '')
                $productos = implode(",", $productoID);

            $subProductos = $subProducto->get_SubProductos($productos);

            foreach ($subProductos as $value)
                $listado .= CHtml::tag('option', array('value' => $value['CODIGO_SUB_PRODUCTO_PK']), $value['DESCRIPCION'], true);

            echo $listado;
        }
    }

    /**
     * Action por defecto cuando se ingresa a la aplicacion.
     */
    public function actionIndex() {
        try {
            
            Usuarios::registrarUsuario($_SERVER["REMOTE_ADDR"], date('Y-m-d H:i:s'));
            
            $fechaActualizacion = Configuracion::get_FechaActualizacion();

            $producto_ = new Productos();
            $productos = $producto_->get_Productos();

            $subProducto = new SubProductos();
            $uen = new Uen();
            $uens = $uen->get_UEN_Todas();

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

                if (Yii::app()->getRequest()->getParam('fecha') != "")
                    $fecha = Yii::app()->getRequest()->getParam('fecha');

                // Numero de los ultimos dias a consultar, 7, 15, 30
                $numeroDias = Yii::app()->getRequest()->getParam('periodo');

                if (Yii::app()->getRequest()->getParam('subproducto') == "" && Yii::app()->getRequest()->getParam('producto') == "") {
                    // Si no se realiza un filtro de ningun producto automaticamente tomara 4G
                    $this->setPageState('producto', '4G,4G FIJO');
                } else {
                    // Si consulta solo por producto
                    if (Yii::app()->getRequest()->getParam('subproducto') == "") {
                        $this->setPageState('producto', implode(",", Yii::app()->getRequest()->getParam('producto')));
                    }
                    // Consulta por subproducto
                    else {
                        $this->setPageState('producto', implode(",", Yii::app()->getRequest()->getParam('subproducto')));
                        $consultaProducto = '';
                    }
                }
            }

            // Si no es una peticion ajax, consultara automaticamente 4G
            else {
                $subProductos = $subProducto->get_SubProductos('');
                $this->setPageState('producto', '4G,4G FIJO');
            }

            $ventas = new Ventas();

            /// Total Ingresadas e Instaladas por Mes
            $mes = date('n', strtotime(date('Y-m-d')));
            if ($fecha != '') {
                $mes = strlen($fecha == 2) ? $fecha : str_replace("0", "", $fecha);
                $fecha = $fecha . "/" . FunsionesSoporte::get_Ultimo_dia_Mes($mes, date('Y')) . "/" . date('Y');
            }

            $totalIngresadasMesActual = $ventas->get_TotalIngresadasMes($this->getPageState('producto'), $uenc, 'Nuevo', '', '', $mes, $consultaProducto);
            $totalInstaladasMesActual = $ventas->get_TotalInstaladasMes($this->getPageState('producto'), $uenc, 'Nuevo', '', '', $mes, $consultaProducto);
            $totalPendientes = $ventas->TotalPendientes($this->getPageState('producto'), $uenc, 'Nuevo', $consultaProducto);

            /// Total Ingresadas e Instaladas por dia - Para el grafico
            if ($fecha == '') { // Si no se esta consultando alguna fecha especifica, tomara los dias seleccionados en el periodo, por defecto 15 dias
                $ventasIngresadas = $ventas->get_Ingresadas($numeroDias, $this->getPageState('producto'), '', '', $uenc, 'Nuevo', $consultaProducto);
                $ventasInstaladas = $ventas->get_Instaladas($numeroDias, $this->getPageState('producto'), '', '', $uenc, 'Nuevo', $consultaProducto);
                $ventasIngresadas = FunsionesSoporte::CompletarDias($ventasIngresadas, 2, $numeroDias);
                $ventasInstaladas = FunsionesSoporte::CompletarDias($ventasInstaladas, 1, $numeroDias);
            } else {
                $ventasIngresadas = $ventas->get_Ingresadas($fecha, $this->getPageState('producto'), '', '', $uenc, 'Nuevo', $consultaProducto);
                $ventasInstaladas = $ventas->get_Instaladas($fecha, $this->getPageState('producto'), '', '', $uenc, 'Nuevo', $consultaProducto);
            }

            $proyectadoCierre = $ventas->get_ProyectadoMes($this->getPageState('producto'), $uenc, 'Nuevo', '', '', $consultaProducto);

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
            $ingresadas_instaladas = $ventas->Ingresadas('', $this->getPageState('producto'), '', $fechaConsulta, $uenc, 'Nuevo', $consultaProducto);
            foreach ($ingresadas_instaladas as $v) {

                $cumplimiento = 0;

                // Presupuesto por plaza
                $presupuesto = round(FunsionesSoporte::get_Presupuesto_X_Plaza($v['PLAZA'], $uenc, $this->getPageState('producto'), date('Y'), $mes, '', $consultaProducto));

                // Presupuesto de las ciudades que no aparecen como plazas
                $presupuestoOtros = round(FunsionesSoporte::get_Presupuesto_X_Plaza('', $uenc, $this->getPageState('producto'), date('Y'), $mes, '', $consultaProducto));

                // Total de instaladas por plaza por mes
                $totalInstaladasPlaza = $ventas->get_TotalInstaladasMes($this->getPageState('producto'), $uenc, 'Nuevo', '', $v['PLAZA'], $mes, $consultaProducto);

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
            $ingresadasInstaladasTotalesOtros = $ventas->IngresadasOtros('', $this->getPageState('producto'), '', $fechaConsulta, $uenc, 'Nuevo', $mes, $consultaProducto);
            $totalInstaladas = 0;
            $totalIngresadas = 0;

            foreach ($ingresadasInstaladasTotalesOtros as $ventasOtros) {
                $totalInstaladas += $ventasOtros['INSTALADAS'];
                $totalIngresadas += $ventasOtros['INGRESADAS'];
            }

            // Total instaladas en el mes para las ciudades que no aparecen como plaza
            $instaladasTotalesOtrosMes = $ventas->IngresadasOtros('', $this->getPageState('producto'), '', '', $uenc, 'Nuevo', $mes, $consultaProducto);
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
            else
                $numeroDias = "Últimos $numeroDias días Hasta el " . date('d-m-Y h:i', strtotime($fechaActualizacion));

            if ($totalPresupuestoPlazas != 0)
                $totalCumplimiento = ($totalInstaladasMesActual / $totalPresupuestoPlazas) * 100;

            // Si se filtra por una fecha especificada el proyectado sera igual al numero de instalaciones de ese mes
            if ($fecha != '')
                $proyectadoCierre = $totalInstaladasMesActual;
            ///---------FIN DE LA CONSULTA DE LOS DATOS DE LAS CIUDADES QUE NO SON MOSTRADAS COMO PLAZAS.(OTROS)

            $arrayDatos = array(
                'fechaactualizacion' => $fechaActualizacion,
                'fechaConsulta' => $fechaConsulta,
                'producto' => $this->getPageState('producto'), // Producto Consultado
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
                'consultaProducto' => $consultaProducto
            );

            if (!Yii::app()->request->isAjaxRequest)
                $this->render('index', $arrayDatos);
            else
                $this->renderPartial('plantillas/ventasGeneral', $arrayDatos);
        } catch (Exception $e) {
            $this->render('error', array('error' => "En este momento estamos actualizando la plataforma, en breve estaremos en linea.", 'detalle' => $e->getMessage()));
        }
    }

    /**
     * visualiza el view de retiros
     */
    public function actionRetiros() {
        try {
            
            var_dump($ventasMeses);

            $this->render('retiros/detalleRetiros');
        } catch (Exception $e) {
            $this->render('error', array('error' => "En este momento estamos actualizando la plataforma, en breve estaremos en linea.", 'detalle' => $e->getMessage()));
        }
    }

    /**
     * visualiza el view de retiros
     */
    public function actionVentasGenerales() {
        try {
            $opcion = "Ingresos ";
            $meses = FunsionesSoporte::get_NombreMes('', true);
            
            $presupuesto = new Presupuestos();
            $presupuestoMeses = $presupuesto->get_Presupuesto('4G,4G FIJO','', 2013, '', '',2,'1');
            
            $ventas = new Ventas();
            $instaladas = $ventas->get_InstaladasTotales_X_Mes('', '4G,4G FIJO', '', '', '', 'Nuevo', '1');
            $ingresadas = $ventas->get_IngresadasTotales_X_Mes('', '4G,4G FIJO', '', '', '', 'Nuevo', '1');
            $anuladas = $ventas->get_Anuladas_X_Mes('', '4G,4G FIJO', '', '', '', 'Nuevo', '1');
            $ingresadasRegional = $ventas->get_IngresadasTotales_X_Mes_X_Regional(7, '4G,4G FIJO', '', '', '', 'Nuevo', '1');
            $instaladasRegional = $ventas->get_InstaladasTotales_X_Mes_X_Regional(7, '4G,4G FIJO', '', '', '', 'Nuevo', '1');
            
            // Generamos las fechas de la categoria del grafico
            $fechaIngreso = "";
            foreach ($ingresadasRegional as $ventas) {
                if ($fechaIngreso != $ventas['FECHA_INGRESO']) {
                    $fechaIngreso = $ventas['FECHA_INGRESO'];
                    $arrayFechasIngresos[] = array('FECHA_INGRESO' => $fechaIngreso);
                }
            }
            
            // Generamos las fechas de la categoria del grafico
            $fechaInstalacion = "";
            foreach ($instaladasRegional as $ventas) {
                if ($fechaInstalacion != $ventas['FECHA_INSTALACION']) {
                    $fechaInstalacion = $ventas['FECHA_INSTALACION'];
                    $arrayFechasInstalaciones[] = array('FECHA_INSTALACION' => $fechaInstalacion);
                }
            }
            
            // Genera el array requerido para el tipo de grafico utilizado
            $ingresadasRegional = RegionalesController::get_Ventas_CombinedColumn($ingresadasRegional,'FECHA_INGRESO');
            $instaladasRegional = RegionalesController::get_Ventas_CombinedColumn($instaladasRegional,'FECHA_INSTALACION');
            
            $this->render('ventas/ventasGenerales', array('meses' => $meses,
                                                          'presupuesto'=>$presupuestoMeses,
                                                          'instaladas'=>$instaladas,
                                                          'ingresadas'=>$ingresadas,
                                                          'anuladas'=>$anuladas,
                                                          'fechasIngresos'=>$arrayFechasIngresos,
                                                          'fechasInstalaciones'=>$arrayFechasInstalaciones,
                                                          'ingresadasRegional'=>$ingresadasRegional,
                                                          'instaladasRegional'=>$instaladasRegional,
                                                          'opcion'=>$opcion));
        } catch (Exception $e) {
            $this->render('error', array('error' => "En este momento estamos actualizando la plataforma, en breve estaremos en linea.", 'detalle' => $e->getMessage()));
        }
    }

    /**
     * visualiza el view de presupuestos
     */
    public function actionPresupuestos() {
        $this->render('ventas/presupuestos');
    }

    /**
     * visualiza el view de detalles por plaza
     */
    public function actionDetallesPlaza() {
        $nombrePlaza = Yii::app()->getRequest()->getParam('plaza');

        $ventas = new Ventas();
        $ventasCanal = $ventas->get_Instaladas_Canales_Mes(Yii::app()->getRequest()->getParam('producto'), Yii::app()->getRequest()->getParam('uen'), $nombrePlaza, 'Nuevo', Yii::app()->getRequest()->getParam('consultaProducto'), Yii::app()->getRequest()->getParam('fechaConsulta'));

        $this->renderPartial('plantillas/detallesPlaza', array('nombrePlaza' => $nombrePlaza, 'cumplimiento' => Yii::app()->getRequest()->getParam('cumplimiento'), 'ventasCanal' => $ventasCanal));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }
    
    /***** Acciones de vistas de regionales *****/
    public function actionDescargasEspecificasRegionalCentro() {
        $this->render('regionales/descargasEspecificasCentro');
    }
}