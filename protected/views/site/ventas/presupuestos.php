<?php
$this->pageTitle = Yii::app()->name . " - Presupuestos";
require_once ('/protected/components/FusionCharts.php');
?>
<h4>Pruebas Presupuesto</h4>
<br>
<hr>

<div>
    <?php
    $strXML2 = FunsionesSoporte::GenerarXML_AngularGauge();
    echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionwidgets/AngularGauge.swf", "", $strXML2, "Presupuestos", 300, 150, false, true) . "</center>";
    ?>
    <br><br>
    
    
    <?php
//    // Categoria de la grafica
//    $categorias = FunsionesSoporte::get_NombreMes('', true);
//
//    // Dataset de la grafica
//    $dataSets = FunsionesSoporte::GenerarValueXMLChart($ventasIngresadas, 'Ingresadas', 'TOTAL_INGRESADA');
//    $dataSets .=FunsionesSoporte::GenerarValueXMLChart($ventasInstaladas, 'Instaladas', 'TOTAL_INSTALADA');
//
//    $strXML = FunsionesSoporte::GenerarXML_Chart('Evolución Ventas', $numeroDiasConsulta, $categorias, $dataSets, "", "");
//    echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/MSLine.swf", "", $strXML, "Ventas", "100%", 435, false, false) . "</center>";
//    ?>

</div>