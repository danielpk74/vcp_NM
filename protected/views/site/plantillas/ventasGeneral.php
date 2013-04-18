<?php require_once ('/protected/components/FusionCharts.php');?>

<div id="filtro">
    <table  class="table table-striped table-bordered table-condensed"> 
        <tr>
            <th style='text-align: center'>PLAZA</th>
            <th style='text-align: center'>INGRESADAS</th>
            <th style='text-align: center'>INSTALADAS</th>                
        </tr>

        <?php foreach ($ventas as $venta) { ?>
            <tr>
                <td><?php echo CHtml::encode($venta['PLAZA']); ?></td>
                <td style='text-align: right'><?php
                    echo CHtml::encode($venta['INGRESADAS']);
                    $totalIngresadas += $venta['INGRESADAS'];
                    ?></td>
                <td style='text-align: right'><?php
                    echo CHtml::encode($venta['INSTALADAS']);
                    $totalInstaladas += $venta['INSTALADAS'];
                    ?></td>
            </tr>   
<?php } ?>

        <tfoot>
            <tr>
                <td class='td-footer'>Total </td>
                <td style='text-align: right'><?php echo CHtml::encode($totalIngresadas); ?></span></td>
                <td style='text-align: right'><?php echo CHtml::encode($totalInstaladas); ?></span></td>
            </tr>
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

<!--    <div class="derecha">
        <a href="#" onClick="javascript:$('#descargarDetalles').toggle('slow');">Descargar Detalles</a>

        <div class="descargas derecha" id="descargarDetalles">
            <?php echo CHtml::imageButton(Yii::app()->request->baseUrl . "/images/excel.png"); ?>
        </div>
    </div>-->

    <?php
    // Categoria de la grafica
    $categorias = FunsionesSoporte::GenerarCategoryXMLChart($ventasIngresadas, 'FECHA_INGRESO');

    // Dataset de la grafica
    $dataSets = FunsionesSoporte::GenerarValueXMLChart($ventasIngresadas, 'Ingresadas', 'TOTAL_INGRESADA');
    $dataSets .=FunsionesSoporte::GenerarValueXMLChart($ventasInstaladas, 'Instaladas', 'TOTAL_INSTALADA');

    $strXML = FunsionesSoporte::GenerarXML_Chart('Evolución Ventas Diarias', date('Y-m-d'), $categorias, $dataSets, "", "");
    echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/MSLine.swf", "", $strXML, "Vibraciones", "100%", 435, false, false) . "</center>";
    ?>