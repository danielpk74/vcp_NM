<!--<h4><?php echo $opcion;?> Por Regional</h4>-->
<!--<hr>-->

<?php

$categorias = FunsionesSoporte::GenerarCategoryXMLChart($fechas, 'FECHA_INGRESO');

$dataSets = "";
for ($i = 0; $i < Count($ventasRegional); $i++) {
    $regional = $ventasRegional[$i]['REGIONAL'];
    unset($ventasRegional[$i]['REGIONAL']);
    $dataSets .= FunsionesSoporte::GenerarValueXMLChart($ventasRegional[$i], $regional, 'CANTIDAD',false);
}

$strXML3 = FunsionesSoporte::GenerarXML_ChartCombinedColumn('Ingresos X Regional',  'Cantidad Ingresada', $categorias, $dataSets);
echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/StackedColumn2DLine.swf", "", $strXML3, "IngresosRegional", '100%', 250, false, true) . "</center>";
?>
