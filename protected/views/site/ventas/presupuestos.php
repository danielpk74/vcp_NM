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

</div>