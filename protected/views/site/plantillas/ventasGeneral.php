<?php require_once ('/protected/components/FusionCharts.php');?>

<div id="filtro">
    <hr>
    <h5  style="text-align: right">Ingresos del día: <?php echo date('d-m-Y', strtotime($fechaConsulta)) ?></h5>
    <table  class="table table-striped table-bordered table-condensed"> 
        <tr>
            <th style='text-align: center'>PLAZA</th>
            <th style='text-align: center'>INGRESADAS</th>
            <th style='text-align: center'>INSTALADAS</th>                
        </tr>

        <?php foreach ($ventas as $venta) { ?>
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
    
    <hr>

  <?php
     if(Count($ventasIngresadas) >= Count($ventasInstaladas))
        $categorias = FunsionesSoporte::GenerarCategoryXMLChart($ventasIngresadas, 'FECHA_INGRESO');
    else
        $categorias = FunsionesSoporte::GenerarCategoryXMLChart($ventasInstaladas, 'FECHA_INSTALACION');

    // Dataset de la grafica
    $dataSets = FunsionesSoporte::GenerarValueXMLChart($ventasIngresadas, 'Ingresadas', 'TOTAL_INGRESADA');
    $dataSets .=FunsionesSoporte::GenerarValueXMLChart($ventasInstaladas, 'Instaladas', 'TOTAL_INSTALADA');

    $strXML = FunsionesSoporte::GenerarXML_Chart('Evolución Ventas', 'Últimos 15 días Hasta el ' . date('d-m-Y h:i', strtotime($fechaactualizacion)), $categorias, $dataSets, "", "");
    echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/MSLine.swf", "", $strXML, "Ventas", "100%", 435, false, false) . "</center>";
    ?>