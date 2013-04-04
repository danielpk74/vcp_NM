<?php
/* @var $this SiteController */
$this->pageTitle = Yii::app()->name;
require_once ('/protected/components/FusionCharts.php');
?>

<h3>Estado Actual</h3>
<hr>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'cssFile' => 'bootstrap',
    'dataProvider' => $ventas,
));
?>

<?php

// Categoria de la grafica
$categorias = FunsionesSoporte::GenerarCategoryXMLChart($ventasIngresadas, 'FECHA_INGRESO');

// Dataset de la grafica
$dataSets = FunsionesSoporte::GenerarValueXMLChart($ventasIngresadas, 'Ingresadas', 'TOTAL_INGRESADA');
$dataSets .=FunsionesSoporte::GenerarValueXMLChart($ventasInstaladas, 'Instaladas', 'TOTAL_INSTALADAS');

$strXML = FunsionesSoporte::GenerarXML_Chart('Evolución Ventas Diarias', date('Y-m-d'), $categorias, $dataSets, "", "");
echo renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/MSLine.swf", "", $strXML, "Vibraciones", "100% ", 300, false, false);
?>