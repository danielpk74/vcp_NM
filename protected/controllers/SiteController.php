<?php

class SiteController extends Controller {

    /**
     * Carga al dropdownlist de subproductos los pertenecientes a un producto enviado por parametro
     */
    public function actionCargarSubProductos() {
        if (Yii::app()->request->isAjaxRequest) {
            $producto = Yii::app()->getRequest()->getParam('producto');
            $producto_ = new Productos();

            if ($producto != '') {
                $subProductos = $producto_->get_SubProductos($producto);

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
            $producto = Yii::app()->getRequest()->getParam('color');
            $producto_ = new Productos();

            if ($producto != '') {
                $subProductos = $producto_->get_SubProductos($producto);
                $subProducto_ = new SubProductos();

                $this->renderPartial('plantillas/ventasGeneral', array('subProductos' => $subProductos, 'subProducto_' => $subProducto_));
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
        $subProducto_ = new SubProductos();

        // Producto a consultar por defecto
        $productoConsulta = "4G";
        $subProductoQuery = "";
        if (Yii::app()->request->isAjaxRequest) {

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
            $subProductos = $producto_->get_SubProductos('');

            $this->setPageState('producto', 'NUMMOV');
        }

        $ventas = new Ventas();

        /// TOTAL INGRESADAS E INSTALADAS MES
        $totalIngresadasMesActual = $ventas->TotalIngresadasMes($this->getPageState('producto'));
        $totalInstaladasMesActual = $ventas->TotalInstaladasMes($this->getPageState('producto'));
        $totalPendientes = $ventas->TotalPendientes($this->getPageState('producto'));

        /// TOTAL INSTALADAS E INGRESADAS POR DIA - GRAFICO
        $ventasIngresadas = $ventas->get_Ingresadas(7, $this->getPageState('producto'));
        $ventasInstaladas = $ventas->get_Instaladas(7, $this->getPageState('producto'));

        // INGRESADAS E INSTALADAS TMP_VENTAS
        $ventasTotales = $ventas->Ingresadas($this->getPageState('producto'));

        if (!Yii::app()->request->isAjaxRequest) {
            $this->render('index', array(
                'fechaactualizacion' => $fechaActualizacion,
                'producto' => $productoConsulta,
                'productomodel' => $producto_,
                'subProducto_' => $subProducto_,
                'productos' => $productos,
                'subProductos' => $subProductos,
                'ventas' => $ventasTotales,
                'ventasIngresadas' => $ventasIngresadas,
                'ingresadasMesActual' => $totalIngresadasMesActual,
                'instaladasMesActual' => $totalInstaladasMesActual,
                'totalPendientes' => $totalPendientes,
                'ventasInstaladas' => $ventasInstaladas));
        } else {
            $this->renderPartial('plantillas/ventasGeneral', array(
                'fechaactualizacion' => $fechaActualizacion,
                'producto' => $productoConsulta,
                'productomodel' => $producto_,
                'subProducto_' => $subProducto_,
                'productos' => $productos,
                'subProductos' => $subProductos,
                'ventas' => $ventasTotales,
                'ventasIngresadas' => $ventasIngresadas,
                'ingresadasMesActual' => $totalIngresadasMesActual,
                'instaladasMesActual' => $totalInstaladasMesActual,
                'totalPendientes' => $totalPendientes,
                'ventasInstaladas' => $ventasInstaladas));
        }
    }

    public function actionActualizar() {
        $fechaActualizacion = new Configuracion();
        $fechaActualizacion = $fechaActualizacion->get_FechaActualizacion();

        $Actualizar = new Actualizar();
        $Actualizar->ActualizarTemporal($fechaActualizacion, $this->getPageState('producto'));

        $this->render('actualizar', array('fechaactualizacion' => $fechaActualizacion));
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