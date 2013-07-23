<?php
$this->pageTitle = Yii::app()->name . " - Pendientes";
require_once ('/protected/components/FusionCharts.php');
?>

<script type="text/javascript">
    $().ready(function() {
        $('#descargarPendientes').live('click', function() {

            $.get('<?php echo CController::createUrl('Site/GenerarExcel') ?>', 'ddd', function(retData) {
                $("body").append("<iframe src='" + retData.url + "'  ></iframe>");
            });

//            $.ajax({'type': 'POST', 'url': '<?php echo CController::createUrl('Site/GenerarExcel') ?>',
//                'success': function(data) {
//                    $('#ifram').attr('src', data);
//                },
//                'cache': false});
        });
    });
</script>

<!--<form name="descarga" action="<?php echo CController::createUrl('Site/GenerarExcel') ?>" >
    <a id='descargarPendientes' style='float: right'><img src="<?php //echo Yii::app()->request->baseUrl;    ?>/images/excel.png" /></a>
</form>-->

<h4>Pendientes en <?php echo $productos . " <span class=\"label label-important\">" . number_format($totalPendientes, 0, ',', '.') . "</span>" ?></h4>
<hr>

<div>

    <div style="float: right; border: 1px;border-style: solid">
        <?php
        $dataSets = "";
        
//        var_dump($pendientesResponsables);
        

            $dataSets.= "<set label='Pendientes Logística' value='" . (($totalPCanalLogistica * 100) / $totalPendientes) . "'/>";
            $dataSets.= "<set label='Pendientes Otras Areas' value='" . (($totalPendientesOtros * 100) / $totalPendientes)  . "'/>";
            $dataSets.= "<set label='Pendientes Nuevos Mercados' value='" . ((($totalPOficinaNM+$totalPRehusadosNM) * 100) / $totalPendientes)  . "'/>";
        
        
        
//        foreach ($pendientesResponsables as $responsable) {
//            $porcentaje = (($responsable['CANTIDAD'] * 100) / $totalPendientes);
//
//            if ($responsable['RESPONSABLE'] == 'Logistica')
//                $nombreResponsable = 'Pendientes Logística';
//            elseif ($responsable['RESPONSABLE'] == 'Credito/Cartera/Fraudes')
//                $nombreResponsable = 'Pendientes Otras Areas';
//            elseif ($responsable['RESPONSABLE'] == 'Rehusados')
//                $nombreResponsable = 'Pendientes Nuevos Mercados';
//
//            $dataSets.= "<set value='$porcentaje' label='" . $nombreResponsable . "'/>";
//            $dataSets.= "<set value='$porcentaje' label='" . $nombreResponsable . "'/>";
//            $dataSets.= "<set value='$porcentaje' label='" . $nombreResponsable . "'/>";
//        }

        $strXML2 = FunsionesSoporte::GenerarXML_Pie2D($dataSets, 'Pendientes X Responsable');
        echo renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/Pie3D.swf", "", $strXML2, "Pie_Responsable", 320, 320, false, true);
        ?>
    </div>

    <div style="padding-top: 100px;padding-bottom: 0px">
        <h6>Responsables</h6>
        <table style="width: 60%;" border="2" cellspacing="2" class="table table-striped table-bordered table-condensed">
            <tr align="center" valign="middle"> 
                <th colspan="2"></th>
                <th colspan="0">4G</th>
                <th colspan="0">4G FIJO</th>
                <th colspan="0">TOTAL</th>
            </tr>

            <tr align="center" valign="middle"> 
                <th rowspan="2">Pendientes Logística</th>
                <th colspan="0">Logística</th>
                <th colspan="0" style="text-align: right"><?php echo number_format($total4G_Logistica, 0, ',', '.') ;
                                                                        $total4G+=$total4G_Logistica;
                            ?></th>
                <th colspan="0" style="text-align: right"><?php echo number_format($total4GLTE_Logistica, 0, ',', '.') ;
                        
                                                                $total4GLTE+=$total4GLTE_Logistica;
                        ?></th>
                <th colspan="0" style="text-align: right"><?php echo number_format($totalPCanalLogistica, 0, ',', '.');
        $total+=$totalPCanalLogistica
        ?></th>
            </tr>
            <tr align="center" valign="middle"> 
                <!--   
                        <th>PEQUE&Ntilde;O</th>
                        <th>GRANDE</th>
                -->
            </tr>

            <tr align="center" valign="middle"> 
                <th rowspan="2">Pendientes Nuevos Mercados</th>
                <th>Oficina</th>
                <th style="text-align: right" style="text-align: right"><?php echo number_format($total4G_OficinaNM, 0, ',', '.');
                                 $total4G+=$total4G_OficinaNM;
                    ?></th>
                <th style="text-align: right" style="text-align: right"><?php echo number_format($total4GLTE_OficinaNM, 0, ',', '.'); 
                
                                $total4GLTE+=$total4GLTE_OficinaNM;
                ?></th>
                <th style="text-align: right" style="text-align: right"><?php echo number_format($totalPOficinaNM, 0, ',', '.');
                    $total+=$totalPOficinaNM;
        ?></th>
            </tr>

            <tr align="center" valign="middle"> 
                <th>Gestión Rehusados</th>
                <th style="text-align: right"><?php echo number_format($total4G_RehusadoNM, 0, ',', '.');
                $total4G+=$total4G_RehusadoNM;
                ?></td>
                <th style="text-align: right"><?php echo number_format($total4GLTE_RehusadosNM, 0, ',', '.'); 
                                                $total4GLTE+=$total4GLTE_RehusadosNM;
                ?></td>
                <th style="text-align: right"><?php echo number_format($totalPRehusadosNM, 0, ',', '.');
                    $total +=$totalPRehusadosNM;
        ?></td>
            </tr>

            <tr align="center" valign="middle"> 
                <th>Pendientes Otras Areas</th>
                <th colspan="1">Crédito/Cartera/Fraudes</th>
                <th style="text-align: right"><?php echo number_format($total4G_Otros, 0, ',', '.');
                                    $total4G+=$total4G_Otros;
                ?></td>
                <th style="text-align: right"><?php echo number_format($total4GLTE_Otros, 0, ',', '.');
                    $total4GLTE+=$total4GLTE_Otros;
        ?></td>
                <th style="text-align: right"><?php
                    echo number_format($totalPendientesOtros, 0, ',', '.');
                    $total+=$totalPendientesOtros;
        ?></th>
            </tr>
            <tr><th colspan="2">Total</th>
                
                <th style="text-align: right" ><span class="label label-important"><?php echo number_format($total4G, 0, ',', '.') ?></span></th>
                <th style="text-align: right" ><span class="label label-important"><?php echo number_format($total4GLTE, 0, ',', '.') ?></span></th>
                <th style="text-align: right" ><span class="label label-important"><?php echo number_format($total, 0, ',', '.') ?></span></th></tr>
        </table>
    </div>

    <br><br><br>
    <hr style="width: 100%">



    <div style="float: right; border: 1px;border-style: solid">
        <?php
        $dataSets = "";
        foreach ($pendientesRegionales as $regional) {
            $porcentaje = (($regional['CANTIDAD'] * 100) / $totalPendientes);
            $dataSets.= "<set value='$porcentaje' label='" . $regional['REGIONAL'] . "'/>";
        }

        $strXML2 = FunsionesSoporte::GenerarXML_Pie2D($dataSets, 'Pendientes X Regional');
        echo renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/Pie3D.swf", "", $strXML2, "Pie_Regional", 320, 320, false, true);
        ?>
    </div>

    <div style="padding-top: 100px">
        <h6>Regionales</h6>
        <table style="width: 50%" class="table table-striped table-bordered table-condensed" > 
            <?php foreach ($pendientesRegionales as $regional) { ?>
                <tr>
                    <th style="text-align: left;height: auto;"> <a id="plaza" href="#" onclick="detallePlaza()"><?php echo $regional['REGIONAL'] ?></a>   </th>   
                    <th  style="width: 200px;text-align: right;"><?php echo number_format($regional['CANTIDAD'], 0, ',', '.');
                            $totalRegionales+=$regional['CANTIDAD'];?></th>
                </tr>
<?php } ?>
                <tr><th>Total</th><th style="text-align: right;"><span class="label label-important"><?php echo number_format($totalRegionales, 0, ',', '.') ?></span></th></tr>
        </table>
    </div>    
    <br><br><br><br>

    <hr style="width: 100%">
    <h6>Plazas</h6>
    <table style="width: 50%" class="table table-striped table-bordered table-condensed" > 
        <?php foreach ($pendientesPlazas as $plaza) { ?>
            <tr>
                <th style="text-align: left;height: auto;"> <a id="plaza" href="#" onclick="detallePlaza()"><?php echo FunsionesSoporte::QuitarAcentos($plaza['PLAZA']) ?></a>   </th>   
                <th  style="width: 200px;text-align: right;"><?php echo number_format($plaza['CANTIDAD'], 0, ',', '.');
                    $totalPlazas+=$plaza['CANTIDAD'];
                ?></th>
            </tr>
<?php } ?>
            <tr><th>Total</th><th style="text-align: right;"><span class="label label-important"><?php echo number_format($totalPlazas, 0, ',', '.') ?></span></th></tr>
    </table>
</div>

