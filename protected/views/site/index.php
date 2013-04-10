<?php
$this->pageTitle = Yii::app()->name;
require_once ('/protected/components/FusionCharts.php');
?>
<h4>Estado Actual de Ventas Diarias - <span class="label label-important" style='font-size: 15px'><?php echo CHtml::encode($producto)?></span>
    <div class="btn-group" style="float: right">
        <button class="btn  " data-toggle="dropdown">Producto <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><?php echo CHtml::linkButton('4G',array('submit'=>array('index','tid'=>'NUMMOV')));?></li>
            <li><?php echo CHtml::linkButton('3G',array('submit'=>array('index','tid'=>'INTMOV')));?></li>
        </ul>
    </div></h4>
<hr>

<table  class="table table-striped table-bordered table-condensed"> 
    <tr>
        <th style='text-align: center'>PLAZA</th>
        <th style='text-align: center'>INGRESADAS</th>
        <th style='text-align: center'>INSTALADAS</th>                
    </tr>
    
    <?php foreach ($ventas as $venta) { ?>
        <tr>
            <td><?php echo CHtml::encode($venta['PLAZA']); ?></td>
            <td style='text-align: right'><?php echo CHtml::encode($venta['INGRESADAS']); ?></td>
            <td style='text-align: right'><?php echo CHtml::encode($venta['INSTALADAS']); ?></td>
        </tr>   
    <?php } ?>

    <tfoot>
        <tr>
            <td class='td-footer'>Total Mes Actual</td>
            <td style='text-align: right'><?php echo CHtml::encode($ingresadasMesActual); ?></span></td>
            <td style='text-align: right'><?php echo CHtml::encode($instaladasMesActual); ?></span></td>
        </tr>
        <tr>
            <td class='td-footer'><b>Pendientes Totales</td>
            <td style='text-align: right'><?php echo CHtml::encode($totalPendientes); ?></span></td>
            <td style='text-align: right'></td>
        </tr>
    </tfoot>

</table>    

<?php
// Categoria de la grafica
$categorias = FunsionesSoporte::GenerarCategoryXMLChart($ventasIngresadas, 'FECHA_INGRESO');

// Dataset de la grafica
$dataSets = FunsionesSoporte::GenerarValueXMLChart($ventasIngresadas, 'Ingresadas', 'TOTAL_INGRESADA');
$dataSets .=FunsionesSoporte::GenerarValueXMLChart($ventasInstaladas, 'Instaladas', 'TOTAL_INSTALADA');

$strXML = FunsionesSoporte::GenerarXML_Chart('Evolución Ventas Diarias', date('Y-m-d'), $categorias, $dataSets, "", "");
echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/MSLine.swf", "", $strXML, "Vibraciones", "100%", 435, false, false) . "</center>";
?>