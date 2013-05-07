<?php require_once ('/protected/components/FusionCharts.php'); ?>


<div id="filtro">
    <hr>
    <h5  style="text-align: right">Ingresos/Instalaciones del día: <?php echo date('d-m-Y', strtotime($fechaConsulta)) ?></h5>
    <table  class="table table-striped table-bordered table-condensed"> 
        <tr>
            <th style='text-align: center'>PLAZA</th>
            <th style='text-align: center'>INGRESADAS - <?php echo CHtml::encode($diaConsulta); ?></th>
            <th style='text-align: center'>INSTALADAS - <?php echo CHtml::encode($diaConsulta); ?></th>                
            <th style='text-align: center'>INSTALADAS MES</th> 
            <th style='text-align: center'>PRESUPUESTO MES</th>                
            <th style='text-align: center'>CUMPLIMIENTO</th>      
        </tr>

        <?php foreach ($ventas as $venta) { ?>
            <tr>
                <td>
                    <?php echo FunsionesSoporte::QuitarAcentos($venta['PLAZA']); ?></td>
                <td style='text-align: right'><?php
                    echo CHtml::encode($venta['INGRESADAS']);
                    $totalIngresadas += $venta['INGRESADAS'];
                    ?>
                </td>
                <td style='text-align: right'><?php
                    echo CHtml::encode($venta['INSTALADAS']);
                    $totalInstaladas += $venta['INSTALADAS'];
                    ?></td>

                <td style='text-align: right'><?php
                    echo CHtml::encode($venta['TOTAL_PLAZA']);
                    $totalPlaza += $venta['TOTAL_PLAZA'];
                    ?></td>

                <td style='text-align: right'><?php
                    echo CHtml::encode($venta['PRESUPUESTO']);
                    $totalPresupuesto += $venta['PRESUPUESTO'];
                    ?></td>

                <td style='text-align: right'><?php
                    echo CHtml::encode($venta['CUMPLIMIENTO']);
                    ?></td>
            </tr>   
        <?php } ?>

        <?php foreach ($ventasOtros as $venta) { ?>
            <tr>
                <td><?php echo FunsionesSoporte::QuitarAcentos($venta['PLAZA']); ?></td>
                <td style='text-align: right'><?php
                    echo CHtml::encode($venta['INGRESADAS']);
                    $totalIngresadas += $venta['INGRESADAS'];
                    ?></td>
                <td style='text-align: right'><?php
                    echo CHtml::encode($venta['INSTALADAS']);
                    $totalInstaladas += $venta['INSTALADAS'];
                    ?></td>

                <td style='text-align: right'><?php
                    echo CHtml::encode($venta['TOTAL_PLAZA']);
                    ?></td>

                <td style='text-align: right'><?php
                    echo CHtml::encode($venta['PRESUPUESTO']);
                    $totalPresupuestoGeneral += $venta['PRESUPUESTO'];
                    ?></td>

                <td style='text-align: right'><?php
                    echo CHtml::encode($venta['CUMPLIMIENTO']);
                    ?></td>
            </tr>   
        <?php } ?>

        <tfoot>
            <tr>
                <td class='td-footer'>Total de <?php echo strtolower(CHtml::encode($diaConsulta)); ?> </td>
                <td style='text-align: right'><span class="label label-important"><?php echo CHtml::encode($totalIngresadas); ?></span></td>
                <td style='text-align: right'><span class="label label-important"><?php echo CHtml::encode($totalInstaladas); ?></span></td>
                <td style='text-align: right'></td>
                <td style='text-align: right'></td>
                <td style='text-align: right'></td>
                
            </tr>
            <tr>
                <td class='td-footer'>Total Mes de <?php echo FunsionesSoporte::get_NombreMes(date('Y-m-d')) ?></td>
                <td style='text-align: right'><span class="label label-important"><?php echo CHtml::encode($ingresadasMesActual); ?></span></td>
                <td style='text-align: right'><span class="label label-important"><?php echo CHtml::encode($instaladasMesActual); ?></span></td>
                <td style='text-align: right'></td>
                <td style='text-align: right'></td>
                <td style='text-align: right'><span class="label label-important"><?php echo CHtml::encode($totalCumplimiento); ?></span></td>
            </tr>

            <tr>
                <td class='td-footer'>Proyectado Mes <?php echo FunsionesSoporte::get_NombreMes(date('Y-m-d')) ?></td>
                <td style='text-align: right'><span class="label label-important"></span></td>
                <td style='text-align: right'><span class="label label-important"><?php echo $proyectadoMesActual ?></span></td>
                <td style='text-align: right'></td>
                <td style='text-align: right'></td>
                <td style='text-align: right'></td>
            </tr>

            <tr>
                <td class='td-footer'><b>Pendientes Totales</td>
                <td style='text-align: right'><span class="label label-important"><?php echo CHtml::encode($totalPendientes); ?></span></td>
                <td style='text-align: right'></td>
                <td style='text-align: right'></td>
                <td style='text-align: right'></td>
                <td style='text-align: right'></td>
            </tr>
        </tfoot>
    </table>    
    <hr>
    
    <?php
    // Categoria de la grafica
    $categorias = FunsionesSoporte::GenerarCategoryXMLChart($ventasIngresadas, 'FECHA_INGRESO');

    // Dataset de la grafica
    $dataSets = FunsionesSoporte::GenerarValueXMLChart($ventasIngresadas, 'Ingresadas', 'TOTAL_INGRESADA');
    $dataSets .=FunsionesSoporte::GenerarValueXMLChart($ventasInstaladas, 'Instaladas', 'TOTAL_INSTALADA');

    $strXML = FunsionesSoporte::GenerarXML_Chart('Evolución Ventas', $numeroDiasConsulta, $categorias, $dataSets, "", "");
    echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/MSLine.swf", "", $strXML, "Ventas", "100%", 435, false, false) . "</center>";
    ?>

</div>