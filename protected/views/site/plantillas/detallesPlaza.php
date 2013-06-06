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
            <li><a href="#canal" data-toggle="tab">Ingresos X Canal</a></li>
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

                <!-- TAB UEN -->
                <div class="tab-pane" id="uens">
<!--                    <table summary="Detalle" class="table table-striped table-bordered table-condensed span-1" >
                        <caption>Instaladas</caption>

                        <thead>
                            <tr>
                                <th></th>
                                <th scope="col">Ingresadas</th>
                                <th scope="col">Instaladas</th>
                                <th scope="col">Anuladas</th>
                                <th scope="col">Pendientes</th>
                            </tr>
                        </thead>

                        <tfoot class="">
                            <tr>
                                <th scope="row">Totales</th><td>5</td><td>7</td><td>6</td><td>2</td>
                            </tr>
                        </tfoot>

                        <tbody>
                            <tr>
                                <th scope="row">Hogares</th><td>-</td><td>-</td><td>-</td><td>-</td>
                            </tr>
                            <tr>
                                <th scope="row">Pymes</th><td>3</td><td>5</td><td>8</td><td>4</td>
                            </tr>
                            <tr>
                                <th scope="row">Corporativos</th><td>4</td><td>4</td><td>7</td><td>3</td>
                            </tr>
                            <tr>
                                <th scope="row">Auto Consumo</th><td>5</td><td>7</td><td>6</td><td>2</td>
                            </tr>
                            
                        </tbody>
                    </table>-->
                </div>
                <!-- FIN TAB UEN -->

                <!-- TAB CANAL -->
                <div class="tab-pane" id="canal">
                    <div style=" height: 500px;  font-size: 12px; overflow: auto; " >
                        <table  class="table table-striped table-bordered table-condensed" > 
                            <tr>
                                <th style='text-align: left'>CANAL</th>   
                                <th style='text-align: left'>INGRESADAS</th>   
                            </tr>
                            
                            

                            <?php 
                            foreach ($ventasCanal as $venta) { ?>
                                <?php if($venta['INGRESADAS'] != 0) {?>
                                  <tr>

                                      <td><?php echo strtoupper(FunsionesSoporte::QuitarAcentos($venta['NOMBRE_CANAL'])); ?></td>
                                     <td style='text-align: right'><?php echo CHtml::encode($venta['INGRESADAS']);?> </td>
                                  </tr>   
                                  <?php } ?>
                            <?php } ?>
                         </table> 
                        
                     </div>
                </div>
        </div>
           
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
        </div>
    </div>
</div>