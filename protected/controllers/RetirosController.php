<?php

class RetirosController extends Controller {

    public static function actionRetirosGeneral($vista) {
        $tipoElemento = "4G,4G FIJO";
        $plaza = "";
        $fecha = "";
        $uenc = "";
        $tipoSolicitud = 'RETIR';
        $tipoCanal = "";
        $regional = "";
        $consultaProducto = '1';
        $mensual = true; // Determina si se va a mostrar la información agrupada por mes (true) o por dias (false)
        $mes = date('n');
        $anio = date('Y');

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
                $anio = implode(",",Yii::app()->getRequest()->getParam('anio'));

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

        $productos = new Productos();
        $productos = $productos->get_Productos();

        $tiposCanales = new TipoCanal();
        $tiposCanales = $tiposCanales->get_Tipo_Canal_Todos();

        $regionales = new Regionales();
        $regionales = $regionales->get_Regionales();

        $subProducto = new SubProductos();
        $subProductos = $subProducto->get_SubProductos('');

//      $vista->setPageState('producto', '4G,4G FIJO');

        $plazas = new Plazas();
        $plazas = PlazasController::get_PlazasSinTilde($plazas->get_Plazas());

        $uenmodel = new Uen();
        $uens = $uenmodel->get_UEN_Todas();

        // ------------- Para generar el 1er grafico
        // Presupuesto
//        $presupuesto = new Presupuestos();
//        $presupuestoMeses = $presupuesto->get_Presupuesto($vista->getPageState('producto'), $uenc, $anio, '', $plaza, 2, $consultaProducto, $regional, $tipoCanal);
        // Ingresos y retiros,--------------------------------------------------------------------------------------------------------------------------------------
//            $ventas = new Ventas();
//            $instaladas = $ventas->get_InstaladasTotales_X_Mes('', $vista->getPageState('producto'), $plaza, '', $uenc, 'Nuevo', $consultaProducto, $mes, $regional, $anio, $tipoCanal);
//            $instaladas = FunsionesSoporte::get_CompletarMesesAtras($instaladas);
//            $instaladas = FunsionesSoporte::get_CompletarMesesIntermedios($total, $instaladas, 'MES', $anio);
        // para generar los meses o los dias para la tabla resumen
        $fecha = $mensual == true ? FunsionesSoporte::get_NombreMes('', true) : FunsionesSoporte::get_DiasMes($mes, $anio);

        $retirosModel = new Retiros();
        $retirosFecha = $retirosModel->get_Retiros_X_Mes($vista->getPageState('producto'), $uenc, $tipoSolicitud, $consultaProducto, $plaza, $regional, $anio, $tipoCanal);
        $retirosFecha = FunsionesSoporte::get_CompletarMesesIntermedios('',$retirosFecha,'MES',$anio);

        $arrayDatos = array(
            'fecha' => $fecha,
            'regionales' => $regionales,
            'retirosFecha' => $retirosFecha,
            'regionales' => $regionales, // Producto Consultado
            'productos' => $productos, // Todos los productos
            'subProductos' => $subProductos, // Todos los subproductos
            'uens' => $uens, // Todas las uens
            'plazas' => $plazas, // Todas las uens
            'tiposCanales' => $tiposCanales,
            'regionalesmodel' => new Regionales(), // Modelo de producto
            'productomodel' => new Productos(), // Modelo de producto
            'subproductomodel' => new SubProductos(), // Subproducto Consultado
            'uenmodel' => new Uen(), // El Modelo de uen
            'plazasmodel' => new Plazas(), // Todas las uens
            'tipocanalmodel' => new TipoCanal(),
        );

        return $arrayDatos;
    }

}