<?php require_once ('/protected/components/FusionCharts.php'); ?>

<div id="detallePlaza" class="modal-body" >
    <div class="tabbable">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Detalles de venta - <?php echo $nombrePlaza ?></h3>
        </div>

        <!-- Items del tab panel -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#cumplimiento" data-toggle="tab">Cumplimiento</a></li>
            <li><a href="#canal" data-toggle="tab">Canales</a></li>
            <li><a href="#ejecutivos" data-toggle="tab">Ejecutivo</a></li>
            <!--<li><a href="#uens" data-toggle="tab">X uen</a></li>-->
        </ul>

        <div class="modal-body">
            <div class="tab-content">

                <!-- TAB CUMPLIMIENTO -->
                <div class="tab-pane active" id="cumplimiento">
                    <!--<h5>Cumplimiento Mes <?php echo FunsionesSoporte::get_NombreMes(date('Y-m-d')) ?></h5>-->
                    <?php
                    $strXML2 = FunsionesSoporte::GenerarXML_AngularGauge(0, 120, $cumplimiento);
                    echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionwidgets/AngularGauge.swf", "", $strXML2, "Presupuestos", 300, 150, false, true) . "</center>";
                    ?>
                    <br><br>
                </div>
                <!-- FIN TAB CUMPLIMIENTO -->

                <!-- TAB CANAL -->
                <div class="tab-pane" id="canal">
                    <div style=" height: 500px; width: 500px;font-size: 12px; overflow: auto; " >
                        <table class="table table-striped table-bordered table-condensed" > 
                            <tr>
                                <th style="text-align: left;height: auto;">CANAL</th>   
                                <th style='text-align: left'>INGRESADAS</th>  
                                <th style='text-align: left'>INSTALADAS</th>  
                            </tr>
                            
                            <?php 
                            $totalIngresadas = 0;
                            $totalInstaladas = 0;
                            $canales = 0;
                            foreach ($ventasCanal as $venta) { ?>
                                <?php if($venta['INGRESADAS'] != 0) {?>
                                  <tr>
                                     <td><?php echo strtoupper(FunsionesSoporte::QuitarAcentos($venta['NOMBRE_CANAL'])); ?></td>
                                     <td style='text-align: center'><?php 
                                            echo CHtml::encode($venta['INGRESADAS']);
                                            $totalIngresadas+= $venta['INGRESADAS'];
                                            $canales++;
                                            ?> 
                                            
                                     </td>
                                     <td style='text-align: center'><?php 
                                            echo CHtml::encode($venta['INSTALADAS']);
                                                $totalInstaladas +=$venta['INSTALADAS'];
                                            ?> 
                                     </td>
                                  </tr>   
                                  <?php } ?>
                            <?php } ?>
                                  <td style='text-align: left'><b><?php echo $canales . " CANALES"; ?> </b></td>
                                  <td style='text-align: left'><b><?php echo "TOTAL ". $totalIngresadas;?> </b></td>
                                  <td style='text-align: left'><b><?php echo "TOTAL ".$totalInstaladas;?> </b></td>
                         </table> 
                     </div>
                </div>
                
                <!-- TAB EJECUTIVO  -->
                <div class="tab-pane" id="ejecutivos">
                    <div style=" height: 500px; width: 500px;font-size: 12px; overflow: auto; " >
                        <table class="table table-striped table-bordered table-condensed" > 
                            <tr>
                                <th style="text-align: left;height: auto;">EJECUTIVO</th>   
                                <th style='text-align: left'>INGRESADAS</th>   
                                <th style='text-align: left'>INSTALADAS</th>   
                            </tr>
                            
                            <?php 
                            $totalIngresadas = 0;
                            $totalInstaladas = 0;
                            $ejecutivos1 = 0;
                            foreach ($ejecutivos as $venta) { ?>
                                <?php if($venta['INGRESADAS'] != 0) {?>
                                  <tr>
                                      <td><?php echo strtoupper(FunsionesSoporte::QuitarAcentos($venta['NOMBRE_EJECUTIVO'])); ?></td>
                                     <td style='text-align: center'><?php 
                                            echo CHtml::encode($venta['INGRESADAS']);
                                            $totalIngresadas+= $venta['INGRESADAS'];
                                             $ejecutivos1++;
                                            ?> 
                                            
                                     </td>
                                     <td style='text-align: center'><?php 
                                             echo CHtml::encode($venta['INSTALADAS']);
                                                $totalInstaladas +=$venta['INSTALADAS'];
                                            ?> 
                                     </td>
                                  </tr>   
                                  <?php } ?>
                            <?php } ?>
                                  <td style='text-align: left'><b><?php echo "TOTAL ". $ejecutivos1 . " CANALES"; ?> </b></td>
                                  <td style='text-align: left'><b><?php echo "TOTAL ".$totalIngresadas;?></b> </td>
                                  <td style='text-align: left'><b><?php echo "TOTAL ".$totalInstaladas;?> </b></td>
                         </table> 
                     </div>
                </div>
        </div>
           
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
        </div>
    </div>
</div>