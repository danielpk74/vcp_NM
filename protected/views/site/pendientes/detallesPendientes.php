<?php
$this->pageTitle = Yii::app()->name . " - Pendientes";
require_once ('/protected/components/FusionCharts.php');
?>
<h4>Pendientes en <?php echo $productos . " ". number_format($totalPendientes,0,',','.')  ?></h4>
<hr>

<div>
    <div style="float: right">
        <?php
        $dataSets = "";
        foreach ($pendientesRegionales as $regional) { 
            $porcentaje = (($regional['CANTIDAD'] * 100)/$totalPendientes);
            $dataSets.= "<set value='$porcentaje' label='".$regional['REGIONAL']."'/>";
        } 
        
        $strXML2 = FunsionesSoporte::GenerarXML_Pie2D($dataSets,'Pendientes X Regional');
        echo renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/Pie2D.swf", "", $strXML2, "Pie_Regional", 320, 320, false, true);
        ?>
    </div>

    <h6>Regionales</h6>
    <table style="width: 50%" class="table table-striped table-bordered table-condensed" > 
        <?php foreach ($pendientesRegionales as $regional) { ?>
            <tr>
                <th style="text-align: left;height: auto;"> <a id="plaza" href="#" onclick="detallePlaza()"><?php echo $regional['REGIONAL'] ?></a>   </th>   
                 <th  style="width: 200px"><?php echo number_format($regional['CANTIDAD'],0,',','.') ?></th>
            </tr>
        <?php } ?>
    </table>
    
    <hr style="width: 50%">
    <h6>Plazas</h6>
    <table style="width: 50%" class="table table-striped table-bordered table-condensed" > 
        <?php foreach ($pendientesPlazas as $plaza) { ?>
            <tr>
                <th style="text-align: left;height: auto;"> <a id="plaza" href="#" onclick="detallePlaza()"><?php echo FunsionesSoporte::QuitarAcentos($plaza['PLAZA']) ?></a>   </th>   
                 <th  style="width: 200px"><?php echo number_format($plaza['CANTIDAD'],0,',','.') ?></th>
            </tr>
        <?php } ?>
    </table>
    
    <div style="float: right">
        <?php
        $dataSets = "";
        foreach ($pendientesResponsables as $responsable) { 
            $porcentaje = (($responsable['CANTIDAD'] * 100)/$totalPendientes);
            $dataSets.= "<set value='$porcentaje' label='".$responsable['RESPONSABLE']."'/>";
        } 
        
        $strXML2 = FunsionesSoporte::GenerarXML_Pie2D($dataSets,'Pendientes X Responsable');
        echo renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/Pie2D.swf", "", $strXML2, "Pie_Responsable", 320, 320, false, true);
        ?>
    </div>
    
    <hr style="width: 50%">
    <h6>Responsables</h6>
    <table style="width: 50%" class="table table-striped table-bordered table-condensed" > 
        <?php foreach ($pendientesResponsables as $responsable) { ?>
            <tr>
                <th style="text-align: left;height: auto;"> <a id="plaza" href="#" onclick="detallePlaza()"><?php echo $responsable['RESPONSABLE'] ?></a>   </th>   
                <th  style="width: 200px"><?php echo number_format($responsable['CANTIDAD'],0,',','.') ?></th>
            </tr>
        <?php } ?>
    </table> 
    
    <hr style="width: 50%">
    <h6>Productos</h6>
    <table style="width: 50%" class="table table-striped table-bordered table-condensed" > 
        <?php foreach ($pendientesProductos as $producto) { ?>
            <tr>
                <th style="text-align: left;height: auto;"> <a id="plaza" href="#" onclick="detallePlaza()"><?php echo $producto['LINEA'] ?></a>   </th>   
                <th  style="width: 200px"><?php echo number_format($producto['CANTIDAD'],0,',','.') ?></th>
            </tr>
        <?php } ?>
    </table> 
    <br><br><br><br>
</div>

