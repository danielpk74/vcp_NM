<?php
$this->pageTitle = Yii::app()->name;
require_once ('/protected/components/FusionCharts.php');
?>

<h3>Estado Actual de Ventas Diarias <input type="date" name="filtro_fecha_venta" value="<?php echo date('Y-m-d'); ?>" style="float: right"/></h3>
<hr>

<table  class="table table-striped table-bordered table-condensed"> 
    <tr>
        <th>PLAZA</th>
        <th>INGRESADAS</th>
        <th>INSTALADAS</th>                
    </tr>

    <?php foreach ($ventas as $venta) { ?>
        <tr>
            <td><?php echo $venta['PLAZA']; ?></td>
            <td><?php echo CHtml::encode($venta['INGRESADAS']); ?></td>
            <td><?php echo CHtml::encode($venta['INSTALADAS']); ?></td>
        </tr>   
    <?php } ?>

    <tfoot>
        <tr>
            <td class='td-footer'>Total Mes Actual</td>
            <td><?php echo CHtml::encode($ingresadasMesActual); ?></span></td>
            <td><?php echo CHtml::encode($instaladasMesActual); ?></span></td>
        </tr>
        <tr>
            <td class='td-footer'><b>Pentientes Totales</td>
            <td class='td-numeros'><?php echo CHtml::encode($totalPendientes); ?></span></td>
            <td class='td-numeros'></td>
        </tr>
    </tfoot>

</table>    


<?php
//$this->widget('zii.widgets.grid.CGridView', array(
//    'cssFile' => Yii::app()->request->baseUrl."/css/gridview.css",
//    'dataProvider'=>$ventas,
//    'columns'=>array(
//        'PLAZA',         
//        'INGRESADAS',    
//        'INSTALADAS',    
//    ),
//));
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