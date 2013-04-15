<?php

class SiteController extends Controller {

    public function actionCargarSubProductos() {
        if (Yii::app()->request->isAjaxRequest) {
            $producto = Yii::app()->getRequest()->getParam('color');
            $producto_ = new Productos();

            if ($producto != '') {
                $subProductos = $producto_->get_SubProductos($producto);

                foreach ($subProductos as $value)
                    $listado .= CHtml::tag('option', array('value' => $value['SUB_PRODUCTO_ID_PK']), $value['DESCRIPCION'], true);

                echo $listado;
            }
        }
    }
    
    public function actionDetallesVentas() {
        
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $fechaActualizacion = new Actualizar();
        $fechaActualizacion = $fechaActualizacion->get_FechaActualizacion();

        $producto_ = new Productos();
        $productos = $producto_->get_Productos();
        $subProducto_ = new SubProductos();

        // Producto a consultar por defecto
        $productoConsulta = "4G";

        // Definimos el tipo elemento a consultar, si no esta dado por get,  asume 4G por defecto
        if (isset($_GET['tid'])) {
            $this->setPageState('tipo_elemento', $_GET['tid']);

            if ($_GET['tid'] != 'NUMMOV')
                $productoConsulta = "3G";
        }
        else
            $this->setPageState('tipo_elemento', 'NUMMOV');

        $ventas = new Ventas();

        /// TOTAL INGRESADAS E INSTALADAS MES
        $totalIngresadasMesActual = $ventas->TotalIngresadasMes($this->getPageState('tipo_elemento'));
        $totalInstaladasMesActual = $ventas->TotalInstaladasMes($this->getPageState('tipo_elemento'));
        $totalPendientes = $ventas->TotalPendientes($this->getPageState('tipo_elemento'));

        /// TOTAL INSTALADAS E INGRESADAS POR DIA - GRAFICO
        $ventasIngresadas = $ventas->get_Ingresadas(7, $this->getPageState('tipo_elemento'));
        $ventasInstaladas = $ventas->get_Instaladas(7, $this->getPageState('tipo_elemento'));

        // INGRESADAS E INSTALADAS TMP_VENTAS
        $ventasTotales = $ventas->Ingresadas($this->getPageState('tipo_elemento'));

        $subProductos = $producto_->get_SubProductos($this->getPageState('tipo_elemento'));
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
    }

    public function actionActualizar() {
        $fechaActualizacion = new Actualizar();
        $fechaActualizacion = $fechaActualizacion->get_FechaActualizacion();
        
        $Actualizar = new Actualizar();
        $Actualizar->ActualizarTemporal($fechaActualizacion,$this->getPageState('tipo_elemento'));

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