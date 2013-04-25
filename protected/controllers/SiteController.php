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
                    $listado .= CHtml::tag('option', array('value' => $value['SUB_PRODUCTO_ID_PK']), $value['DESCRIPCION'], true);

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
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $configuracion = new Configuracion();
        $fechaActualizacion = $configuracion->get_FechaActualizacion();

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
        $uenp  = "";
        $tipo_solicitud = 'Nuevo';

        // ACCION FILTRAR
        if (Yii::app()->request->isAjaxRequest) {

            if (Yii::app()->getRequest()->getParam('uen') != "")
                $uenp = Yii::app()->getRequest()->getParam('uen');

            // Si consulta solo por producto
            if (Yii::app()->getRequest()->getParam('subproducto') == "") {
                $this->setPageState('producto', Yii::app()->getRequest()->getParam('producto'));
            } else { // Consultamos por subproducto {
                $this->setPageState('producto', Yii::app()->getRequest()->getParam('subproducto'));
            }

            if (Yii::app()->getRequest()->getParam('producto') != 'NUMMOV')
                $productoConsulta = "3G";
        }
        else {
            $subProductos = $subProducto->get_SubProductos('');
            $this->setPageState('producto', 'NUMMOV');
        }

        $ventas = new Ventas();

        /// TOTAL INGRESADAS E INSTALADAS MES
        $totalIngresadasMesActual = $ventas->TotalIngresadasMes($this->getPageState('producto'), $uenp, 'Nuevo');
        $totalInstaladasMesActual = $ventas->TotalInstaladasMes($this->getPageState('producto'), $uenp, 'Nuevo');
        $totalPendientes = $ventas->TotalPendientes($this->getPageState('producto'), $uenp, 'Nuevo');

        /// TOTAL INSTALADAS E INGRESADAS POR DIA - GRAFICO
        $ventasIngresadas = $ventas->get_Ingresadas(15, $this->getPageState('producto'), '', '', $uenp, 'Nuevo');
        $ventasInstaladas = $ventas->get_Instaladas(15, $this->getPageState('producto'), '', '', $uenp, 'Nuevo');

        // INGRESADAS E INSTALADAS DEL DIA/DIA ANTERIOR
        if (date('H:i') < '18:50')
            $fechaConsulta = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d'))));
        else
            $fechaConsulta = date('Y-m-d');
        
        $ventasTotales = $ventas->Ingresadas('', $this->getPageState('producto'), '', $fechaConsulta, $uenp, 'Nuevo');
        $ventasTotalesOtros = $ventas->IngresadasOtros('', $this->getPageState('producto'), '', $fechaConsulta, $uenp, 'Nuevo');
       
        $totalInstaladas = 0;
        $totalIngresadas = 0;
        foreach ($ventasTotalesOtros as $ventasOtros)
        {
           $totalInstaladas += $ventasOtros['INSTALADAS'];
           $totalIngresadas += $ventasOtros['INGRESADAS'];
        }

        $TotalesOtros[] = array('PLAZA'=>'Otros','INGRESADAS'=>$totalIngresadas,'INSTALADAS'=>$totalInstaladas);
        if (!Yii::app()->request->isAjaxRequest) {
            $this->render('index', array(
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
                'ingresadasMesActual' => $totalIngresadasMesActual,
                'instaladasMesActual' => $totalInstaladasMesActual,
                'totalPendientes' => $totalPendientes,
                'uenmodel' => $uen,
                'uens' => $uens,
                'ventasInstaladas' => $ventasInstaladas));
        } else {
            $this->renderPartial('plantillas/ventasGeneral', array(
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
                'ingresadasMesActual' => $totalIngresadasMesActual,
                'instaladasMesActual' => $totalInstaladasMesActual,
                'totalPendientes' => $totalPendientes,
                'uenmodel' => $uens,
                'uens' => $uens,
                'ventasInstaladas' => $ventasInstaladas));
        }
    }

    public function actionActualizar() {
        $fechaActualizacion = new Configuracion();
        $fechaActualizacion = $fechaActualizacion->get_FechaActualizacion();

        $Actualizar = new Actualizar();
        $Actualizar->ActualizarTemporal($fechaActualizacion, $this->getPageState('producto'));

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