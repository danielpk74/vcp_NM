<?php

class SiteController extends Controller {

    /**
     * Carga al dropdownlist de subproductos los pertenecientes a un producto enviado por parametro
     */
    public function actionCargarSubProductos() {
        if (Yii::app()->request->isAjaxRequest) {
            $productoID = Yii::app()->getRequest()->getParam('producto');
            $subProducto = new SubProductos();

            if ($productoID != '') {
                $subProductos = $subProducto->get_SubProductos($productoID);

                foreach ($subProductos as $value)
                    $listado .= CHtml::tag('option', array('value' => $value['CODIGO_SUB_PRODUCTO_PK']), $value['DESCRIPCION'], true);

                echo $listado;
            }
        }
    }

    /**
     * 
     */
    public function actionActualizarDetallesVentas() {
        if (Yii::app()->request->isAjaxRequest) {
            $productoID = Yii::app()->getRequest()->getParam('color');
            $subProducto = new SubProductos();

            if ($productoID != '') {
                $subProductos = $subProducto->get_SubProductos($productoID);
                $this->renderPartial('plantillas/ventasGeneral', array('subProductos' => $subProductos, 'subProducto_' => $subProducto));
            }
        }
    }

    /**
     * Action por defecto cuando se ingresa a la aplicacion.
     */
    public function actionIndex() {
        try {
            $fechaActualizacion = Configuracion::get_FechaActualizacion();

            $producto_ = new Productos();
            $productos = $producto_->get_Productos();

            $subProducto = new SubProductos();
            $uen = new Uen();
            $uens = $uen->get_UEN_Todas();

            // Producto a consultar por defecto
            $productoConsulta = "4G";
            $subProductoQuery = "";

            $tipoElemento = "";
            $plaza = "";
            $fecha = "";
            $uenp = "";
            $tipo_solicitud = 'Nuevo';
            $numeroDias = 15;

            // ACCION FILTRAR
            // Si la peticion viene por via ajax
            if (Yii::app()->request->isAjaxRequest) {
                if (Yii::app()->getRequest()->getParam('uen') != "")
                    $uenp = Yii::app()->getRequest()->getParam('uen');

                // Numero de los ultimos dias a consultar, 7, 15, 30
                $numeroDias = Yii::app()->getRequest()->getParam('periodo');

                // Si consulta solo por producto
                if (Yii::app()->getRequest()->getParam('subproducto') == "") {
                    $this->setPageState('producto', Yii::app()->getRequest()->getParam('producto'));
                }
                // Consultamos por subproducto
                else {
                    $this->setPageState('producto', Yii::app()->getRequest()->getParam('subproducto'));
                }

                if (Yii::app()->getRequest()->getParam('producto') != 'NUMMOV')
                    $productoConsulta = "3G";
            }

            // Si no es una peticion ajax, consultara automaticamente 4G
            else {
                $subProductos = $subProducto->get_SubProductos('');
                $this->setPageState('producto', 'NUMMOV');
            }

            $ventas = new Ventas();

            /// Total Ingresadas e Instaladas por Mes
            $totalIngresadasMesActual = $ventas->get_TotalIngresadasMes($this->getPageState('producto'), $uenp, 'Nuevo');
            $totalInstaladasMesActual = $ventas->get_TotalInstaladasMes($this->getPageState('producto'), $uenp, 'Nuevo');
            $totalPendientes = $ventas->TotalPendientes($this->getPageState('producto'), $uenp, 'Nuevo');

            /// Total Ingresadas e Instaladas por dia - Para el grafico
            $ventasIngresadas = $ventas->get_Ingresadas($numeroDias, $this->getPageState('producto'), '', '', $uenp, 'Nuevo');
            $ventasInstaladas = $ventas->get_Instaladas($numeroDias, $this->getPageState('producto'), '', '', $uenp, 'Nuevo');
            $ventasIngresadas = FunsionesSoporte::CompletarDias($ventasIngresadas, 2, $numeroDias);
            $ventasInstaladas = FunsionesSoporte::CompletarDias($ventasInstaladas, 1, $numeroDias);

            $proyectadoCierre = $ventas->get_ProyectadoMes($this->getPageState('producto'), $uenp, 'Nuevo');


            if (date('A', strtotime($fechaActualizacion)) == 'AM' || date('Y-m-d', strtotime($fechaActualizacion)) != date('Y-m-d')) {
                $fechaConsulta = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d'))));
                $diaConsulta = "AYER";
            } else {
                $fechaConsulta = date('Y-m-d');
                $diaConsulta = "HOY";
            }

            ///// Para las Ingresadas e instaladas del dia anterior
            /// Ingresadas/Instaladas de plazas principales
            $ingresadas_instaladas = $ventas->Ingresadas('', $this->getPageState('producto'), '', $fechaConsulta, $uenp, 'Nuevo');
            foreach ($ingresadas_instaladas as $v) {

                $cumplimiento = 0;

                // Presupuesto por plaza
                $presupuesto = round(FunsionesSoporte::get_Presupuesto_X_Plaza($v['PLAZA'], $uenp, $this->getPageState('producto'), date('Y'), date('n')));

                // Presupuesto de las ciudades que no aparecen como plazas
                $presupuestoOtros = round(FunsionesSoporte::get_Presupuesto_X_Plaza('', $uenp, $this->getPageState('producto'), date('Y'), date('n')));

                // Total de instaladas por plaza por mes
                $totalInstaladasPlaza = $ventas->get_TotalInstaladasMes($this->getPageState('producto'), $uenp, 'Nuevo', '', $v['PLAZA']);

                if ($presupuesto != 0)
                    $cumplimiento = ($totalInstaladasPlaza / $presupuesto) * 100;

                // Para totalizar el presupuesto de las plazas, los primeros 10 dias debe ser igual al proyectado para el mes
                $totalPresupuestoPlazas += $presupuesto;

                $ventasTotales[] = array('PLAZA' => $v['PLAZA'],
                    'INGRESADAS' => $v['INGRESADAS'],
                    'INSTALADAS' => $v['INSTALADAS'],
                    'TOTAL_PLAZA' => $totalInstaladasPlaza,
                    'PRESUPUESTO' => number_format($presupuesto, '0', ',', '.'),
                    'CUMPLIMIENTO' => number_format($cumplimiento, '0', ',', '.') . "%");
            }


            ///---------COMIENZA LA CONSULTA DE LOS DATOS DE LAS CIUDADES QUE NO SON MOSTRADAS COMO PLAZAS.(OTROS)
            // Ingresadas/Instaladas de las ciudades que no aparecen como plazas
            $ingresadasInstaladasTotalesOtros = $ventas->IngresadasOtros('', $this->getPageState('producto'), '', $fechaConsulta, $uenp, 'Nuevo');
            $totalInstaladas = 0;
            $totalIngresadas = 0;

            foreach ($ingresadasInstaladasTotalesOtros as $ventasOtros) {
                $totalInstaladas += $ventasOtros['INSTALADAS'];
                $totalIngresadas += $ventasOtros['INGRESADAS'];
            }

            // Total instaladas en el mes para las ciudades que no aparecen como plaza
            $instaladasTotalesOtrosMes = $ventas->IngresadasOtros('', $this->getPageState('producto'), '', '', $uenp, 'Nuevo');
            foreach ($instaladasTotalesOtrosMes as $ventasOtros) {
                $totalInstaladasOtrosMes += $ventasOtros['INSTALADAS'];
            }

            // Para totalizar el presupuesto de las ciudades que no aparecen como plaza, los primeros 10 dias debe ser igual al proyectado para el mes
            $totalPresupuestoPlazas += $presupuestoOtros;

            if ($presupuestoOtros != 0)
                $cumplimiento = ($totalInstaladasOtrosMes / $presupuestoOtros) * 100;

            $TotalesOtros[] = array('PLAZA' => 'Otros', 'INGRESADAS' => $totalIngresadas, 'INSTALADAS' => $totalInstaladas, 'TOTAL_PLAZA' => $totalInstaladasOtrosMes, 'PRESUPUESTO' => number_format($presupuestoOtros, '0', ',', '.'), 'CUMPLIMIENTO' => number_format($cumplimiento, '0', ',', '.') . "%");

            ///----- Ingresadas/Instaladas de otras plazas --------///
            if ($numeroDias == "")
                $numeroDias = "Mes Actual";
            else
                $numeroDias = "Últimos $numeroDias días Hasta el " . date('d-m-Y h:i', strtotime($fechaActualizacion));

            if ($proyectadoCierre != 0)
                $totalCumplimiento = ($totalInstaladasMesActual / $proyectadoCierre) * 100;

            $arrayDatos = array(
                'fechaactualizacion' => $fechaActualizacion,
                'fechaConsulta' => $fechaConsulta,
                'producto' => $productoConsulta,
                'productomodel' => $producto_,
                'subProducto_' => $subProducto,
                'productos' => $productos,
                'subProductos' => $subProductos,
                'ventas' => $ventasTotales,
                'ventasOtros' => $TotalesOtros,
                'ventasIngresadas' => $ventasIngresadas,
                'ingresadasMesActual' => number_format($totalIngresadasMesActual, '0', ',', '.'),
                'instaladasMesActual' => number_format($totalInstaladasMesActual, '0', ',', '.'),
                'totalPendientes' => number_format($totalPendientes, '0', ',', '.'),
                'proyectadoMesActual' => number_format($proyectadoCierre, '0', ',', '.'),
                'totalCumplimiento' => number_format($totalCumplimiento, '0', ',', '.') . "%",
                'uens' => $uens,
                'uenmodel' => $uen,
                'ventasInstaladas' => $ventasInstaladas,
                'diaConsulta' => $diaConsulta,
                'numeroDiasConsulta' => $numeroDias,
                'presupuestoTotalPlaza' => number_format($totalPresupuestoPlazas, '0', ',', '.'),
            );

            if (!Yii::app()->request->isAjaxRequest)
                $this->render('index', $arrayDatos);
            else
                $this->renderPartial('plantillas/ventasGeneral', $arrayDatos);
            
            
        } catch (Exception $e) {
            $this->render('error', array('error' => "En este momento estamos actualizando la plataforma, en breve estaremos en linea.", 'detalle' => $e->getMessage()));
        }
    }

    public function actionActualizar() {
        $Actualizar = new Actualizar();
        $Actualizar->ActualizarTemporal();

        $fechaActualizacion = date('Y-m-d');

        $this->render('actualizar', array('fechaactualizacion' => $fechaActualizacion));
    }

    public function actionPresupuestos() {
        $this->render('ventas/presupuestos');
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

}