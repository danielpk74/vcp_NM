<?php require_once ('/protected/components/FusionCharts.php'); ?>
<div id="detallesVentas">
    <?php
    $categorias = FunsionesSoporte::GenerarCategoryXMLChart($meses, 'mes');

    $dataSets = FunsionesSoporte::GenerarValueXMLChart($presupuesto, 'Presupuesto', 'CANTIDAD');
    $dataSets .= FunsionesSoporte::GenerarValueXMLChart($instaladas, 'Instaladas', 'CANTIDAD', true);
    $dataSets .= FunsionesSoporte::GenerarValueXMLChart($ingresadas, 'Ingresadas', 'CANTIDAD', true);
    $dataSets .= FunsionesSoporte::GenerarValueXMLChart($anuladas, 'Anuladas', 'CANTIDAD', true);
    $dataSets .= FunsionesSoporte::GenerarValueXMLChart($pendientes, 'Pendientes', 'CANTIDAD', true);

    $strXML2 = FunsionesSoporte::GenerarXML_Combi2D2('Evolución de Ventas General Mensual', $categorias, $dataSets);
    echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/ScrollCombiDY2D.swf", "", $strXML2, "VentasGenerales", '100%', 250, false, true) . "</center>";
    ?>

    <table summary="Detalle" class="table table-striped table-bordered table-condensed " >
        <caption></caption>

        <thead>
            <tr class="une">
                <th></th>
                <?php
                foreach ($meses as $key => $mes) {
                    echo "<th scope='col'>$mes</th>";
                }
                ?>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <th scope="row">Ingresadas</th>
                <?php
                $ingresos = array();
                $totalIngreadas = 0;
                foreach ($ingresadas as $ingresada) {
                    echo "<td>" . number_format($ingresada['CANTIDAD'], '0', ',', '.') . "</td>";
                    $ingresos[] = $ingresada['CANTIDAD'];
                    $totalIngresadas += $ingresada['CANTIDAD'];
                }
                ?>

                <?php
                for ($i = Count($ingresadas); $i <= 11; $i++)
                    echo "<td>0</td>";
                ?>

                <th><?php echo number_format($totalIngresadas, '0', ',', '.'); ?></th>

            </tr>

            <tr>
                <th scope="row">Presupuesto</th>
                <?php
                $presupuestos = array();
                $totalPresupuesto = 0;
                foreach ($presupuesto as $presupuesto) {
                    echo "<td>" . number_format($presupuesto['CANTIDAD'], '0', ',', '.') . "</td>";
                    $presupuestos[] = $presupuesto['CANTIDAD'];
                    $totalPresupuesto += $presupuesto['CANTIDAD'];
                }
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
                <th scope="row">Instaladas</th>
                <?php
                $instalaciones = array();
                $totalInstaladas = 0;
                foreach ($instaladas as $instaladas) {
                    echo "<td>" . number_format($instaladas['CANTIDAD'], '0', ',', '.') . "</td>";
                    $instalaciones[] = $instaladas['CANTIDAD'];
                    $totalInstaladas += $instaladas['CANTIDAD'];
                }
                ?>
                <?php
                for ($i = Count($ingresadas); $i <= 11; $i++)
                    echo "<td>0</td>";
                ?>
                <th><?php echo number_format($totalInstaladas, '0', ',', '.') ?></th>  
            </tr>

            <tr>
                <th scope="row">Pendientes</th>
                <?php
                $aPendientes = array();
                $totalPendientes = 0;
                foreach ($pendientes as $pendiente) {
                    echo "<td>" . number_format($pendiente['CANTIDAD'], '0', ',', '.') . "</td>";
                    $aPendientes[] = $pendiente['CANTIDAD'];
                    $totalPendientes += $pendiente['CANTIDAD'];
                }
                ?>
                <?php
                for ($i = Count($aPendientes); $i <= 11; $i++)
                    echo "<td>0</td>";
                ?>
                <th><?php echo number_format($totalPendientes, '0', ',', '.') ?></th>  
            </tr>

            <tr>
                <th scope="row">Anuladas</th>
                <?php
                $totalAnuladas = 0;

                foreach ($anuladas as $anulada) {
                    echo "<td>" . number_format($anulada['CANTIDAD'], '0', ',', '.') . "</td>";
                    $totalAnuladas += $anulada['CANTIDAD'];
                }
                ?>
                <?php
                for ($i = count($anuladas); $i <= 11; $i++)
                    echo "<td>0</td>";
                ?>
                <th><?php echo number_format($totalAnuladas, '0', ',', '.') ?></th>  
            </tr>

            <tr>
                <th scope="row">Cumplimiento</th>
                <?php
                for ($i = 0; $i < Count($instalaciones); $i++) {
                    echo "<td>" . FunsionesSoporte::get_Porcentaje($instalaciones[$i], $presupuestos[$i], 1) . "%</td>";
                }
                ?>
                <?php
                for ($i = Count($ingresadas); $i <= 11; $i++)
                    echo "<td>0</td>";
                ?>  
                <th><?php echo FunsionesSoporte::get_Porcentaje($totalInstaladas, $totalPresupuesto, 1) . "%" ?></th>  
            </tr>

            <tr>
                <th scope="row">Eficiencia</th>
                <?php
                for ($i = 0; $i < Count($ingresos); $i++) {
                    echo "<td>" . FunsionesSoporte::get_Porcentaje($instalaciones[$i], $ingresos[$i], 1) . "%</td>";
                }
                ?>

                <?php
                for ($i = Count($ingresadas); $i <= 11; $i++)
                    echo "<td>0</td>";
                ?>

                <th><?php echo FunsionesSoporte::get_Porcentaje($totalInstaladas, $totalIngreadas, 1) . "%" ?></th>  
            </tr>
        </tbody>
    </table>

    <br>
    <hr>
    <br>

    <?php require_once ('detallesRegional.php'); ?>    
</div>