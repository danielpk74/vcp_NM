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
                <li><a href="#canal" data-toggle="tab">X Canal</a></li>
                <li><a href="#uens" data-toggle="tab">X uen</a></li>
            </ul>
        
        <div class="modal-body">
            <div class="tab-content">
                
                <!-- TAB CUMPLIMIENTO -->
                <div class="tab-pane active" id="cumplimiento">
                    <!--<h5>Cumplimiento Mes <?php echo FunsionesSoporte::get_NombreMes(date('Y-m-d')) ?></h5>-->
                        <?php
                            $strXML2 = FunsionesSoporte::GenerarXML_AngularGauge(0,120,$cumplimiento);
                            echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionwidgets/AngularGauge.swf", "", $strXML2, "Presupuestos", 300, 150, false, true) . "</center>";
                        ?>
                        <br><br>
                </div>
                <!-- FIN TAB CUMPLIMIENTO -->
                
                 <!-- TAB UEN -->
                <div class="tab-pane" id="uens">
                  <div class="alert alert-error">En Contrucción</div>
<!--                   <table  class="table table-striped table-bordered table-condensed"> 
                        <tr>
                            <th style='text-align: center'>UEN</th>
                            <th style='text-align: center'>CANTIDAD</th>   
                        </tr>

                        <?php // foreach ($ventas as $venta) { ?>
                        <tr>
                            <td><a id="plaza" href="#modalDetallesPlaza" role="button" data-toggle="modal"><?php echo FunsionesSoporte::QuitarAcentos($venta['PLAZA']); ?></a></td>

                            <td style='text-align: right'><?php
                                echo CHtml::encode($venta['INGRESADAS']);
                                $totalIngresadas += $venta['INGRESADAS'];
                                ?>
                            </td>
                        </tr>   
                        <?php // } ?>
                    </table>   -->
                </div>
                <!-- FIN TAB UEN -->

                <!-- TAB CANAL -->
                <div class="tab-pane" id="canal">
                     <div class="alert alert-error">En Contrucción</div>
<!--                    <table  class="table table-striped table-bordered table-condensed"> 
                        <tr>
                            <th style='text-align: center'>MES</th>
                            <th style='text-align: center'>CANAL</th>   
                            <th style='text-align: center'>CORPORATIVOS</th>   
                            <th style='text-align: center'>HOGARES</th>   
                            <th style='text-align: center'>PYMES</th>   
                            <th style='text-align: center'>AUTOCONSUMO</th>   
                        </tr>

                        <?php // foreach ($ventas as $venta) { ?>
                        <tr>
                            <td><a id="plaza" href="#modalDetallesPlaza" role="button" data-toggle="modal"><?php echo FunsionesSoporte::QuitarAcentos($venta['PLAZA']); ?></a></td>

                            <td style='text-align: right'><?php
                                echo CHtml::encode($venta['INGRESADAS']);
                                $totalIngresadas += $venta['INGRESADAS'];
                                ?>
                            </td>
                            <td style='text-align: right'><?php
                                echo CHtml::encode($venta['INGRESADAS']);
                                $totalIngresadas += $venta['INGRESADAS'];
                                ?>
                            </td>
                            <td style='text-align: right'><?php
                                echo CHtml::encode($venta['INGRESADAS']);
                                $totalIngresadas += $venta['INGRESADAS'];
                                ?>
                            </td>
                            <td style='text-align: right'><?php
                                echo CHtml::encode($venta['INGRESADAS']);
                                $totalIngresadas += $venta['INGRESADAS'];
                                ?>
                            </td>
                            <td style='text-align: right'><?php
                                echo CHtml::encode($venta['INGRESADAS']);
                                $totalIngresadas += $venta['INGRESADAS'];
                                ?>
                            </td>
                        </tr>   
                        <?php // } ?>
                    </table>   -->
                    <!-- FIN TAB CANAL -->
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
        </div>
    </div>
</div>