<?php
$this->pageTitle = Yii::app()->name;
require_once ('/protected/components/FusionCharts.php');
?>

<h3>Estado Actual de Ventas <input type="date" name="filtro_fecha_venta" value="<?php echo date('Y-m-d');?>" style="float: right"/></h3>
<hr>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'cssFile' => Yii::app()->request->baseUrl."/css/gridview.css",
//    'content:html',
    'enablePagination' => 'false',
    'dataProvider' => $ventas,
));
?>

<?php
// Categoria de la grafica
$categorias = FunsionesSoporte::GenerarCategoryXMLChart($ventasIngresadas, 'FECHA_INGRESO');

// Dataset de la grafica
$dataSets = FunsionesSoporte::GenerarValueXMLChart($ventasIngresadas, 'Ingresadas', 'TOTAL_INGRESADA');
$dataSets .=FunsionesSoporte::GenerarValueXMLChart($ventasInstaladas, 'Instaladas', 'TOTAL_INSTALADA');

$strXML = FunsionesSoporte::GenerarXML_Chart('Evolución Ventas Diarias', date('Y-m-d'), $categorias, $dataSets, "", "");
echo renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/MSLine.swf", "", $strXML, "Vibraciones", "100% ", 450, false, false);
?>