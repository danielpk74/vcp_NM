<?php
require_once ('/protected/components/FusionCharts.php');
?>
<div id="detallesRetiros">
    <?php
        
    $categorias = FunsionesSoporte::GenerarCategoryXMLChart($fecha, 'mes');
    $dataSets = FunsionesSoporte::GenerarValueXMLChart($retirosFecha, 'Retiros', 'CANTIDAD', true);

    $strXML2 = FunsionesSoporte::GenerarXML_Combi2D2('Evolución de Retiros Mensual', $categorias, $dataSets);
    echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/ScrollCombiDY2D.swf", "", $strXML2, "RetirosGenerales", '100%', 250, false, true) . "</center>";
    ?>

    <table summary="Detalle" class="table table-striped table-bordered table-condensed " >
        <caption></caption>

        <thead>
            <tr class="une">
                <th></th>
                <?php
                foreach ($fecha as $fecha) {
                    echo "<th scope='col'>$fecha</th>";
                }
                ?>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <th scope="row">Retiros</th>
                <?php
                $retiros = array();
                $totalRetiros= 0;
                foreach ($retirosFecha as $retiro) {
                    echo "<td>" . number_format($retiro['CANTIDAD'], '0', ',', '.') . "</td>";
                    $totalRetiros += $retiro['CANTIDAD'];
                }
                ?>

                <?php
                for ($i = Count($retirosFecha); $i <= 11; $i++)
                    echo "<td>0</td>";
                ?>

                <th><?php echo number_format($totalRetiros, '0', ',', '.'); ?></th>

            </tr>

            <tr>
                <th scope="row">Presupuesto</th>
                <?php
//                $presupuestos = array();
//                $totalPresupuesto = 0;
//                foreach ($presupuesto as $presupuesto) {
//                    echo "<td>" . number_format($presupuesto['CANTIDAD'], '0', ',', '.') . "</td>";
//                    $presupuestos[] = $presupuesto['CANTIDAD'];
//                    $totalPresupuesto += $presupuesto['CANTIDAD'];
//                }
                ?>

                <?php
                if (Count($presupuesto) == 0) {
                    for ($i = Count($presupuesto); $i <= 11; $i++)
                        echo "<td>0</td>";
                }
                ?>
                <th><?php echo number_format($totalPresupuesto, '0', ',', '.') ?></th>  
            </tr>

           

           <tr>
                <th scope="row">Cumplimiento</th>
                <?php
//                for ($i = 0; $i < Count($instalaciones); $i++) {
//                    echo "<td>" . FunsionesSoporte::get_Porcentaje($instalaciones[$i], $presupuestos[$i], 1) . "%</td>";
//                }
                ?>
                <?php
                for ($i = Count($ingresadas); $i <= 11; $i++)
                    echo "<td>0</td>";
                ?>  
                <th><?php // echo FunsionesSoporte::get_Porcentaje($totalInstaladas, $totalPresupuesto, 1) . "%" ?></th>  
            </tr>

           
        </tbody>
    </table>
</div>

<div>
    <?php
//   set_time_limit(0);
//        $geo = new ArcgisGeocode("380 New York St, Redlands, CA, 92373"); 
//        $geo->debug = true; 
//        print "<pre>"; 
//        print_r($geo->output); 
//        print_r($geo->output->address); 
//        print_r($geo->output->latlon); 
//        set_time_limit(60);
////        ?>
    
    
    

</div>