<?php
$this->pageTitle = Yii::app()->name . " - Presupuestos";
require_once ('/protected/components/FusionCharts.php');
?>

<h4>Descargas Especificas</h4>
<hr>

<div class="accordion" id="accordion2">
    <div class="accordion-group">
        <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse1">
                MARLEN PENUELA
            </a>
        </div>
        <div id="collapse1" class="accordion-body collapse ">
            <div class="accordion-inner">
                <a href="http://dcastamo/Ventas/Ventas_Especificas/Marlen Peñuela Resumen Asesores.PDF" target="blank">
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/pdf.png"/>Resumen Asesores
                </a>
            </div>
        </div>
    </div>

    <div class="accordion-group">
        <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse2">
                ALVARO ESQUIVIA
            </a>
        </div>
        <div id="collapse2" class="accordion-body collapse ">
            <div class="accordion-inner">
                <a href="http://dcastamo/Ventas/Ventas_Especificas/Alvaro Jose Esquivia Resumen Asesores.PDF" target="blank">
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/pdf.png"/>Resumen Asesores

                   
                </a>
            </div>
        </div>
    </div>

    <div class="accordion-group">
        <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse3">
                FABIO FORERO
            </a>
        </div>
        <div id="collapse3" class="accordion-body collapse ">
            <div class="accordion-inner">
                <a href="http://dcastamo/Ventas/Ventas_Especificas/Fabio Forero Resumen Asesores.PDF" target="blank">
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/pdf.png"/>Resumen Asesores
                </a>
            </div>
        </div>
    </div>

    <div class="accordion-group">
        <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse4">
                YHUM ALARCON
            </a>
        </div>
        <div id="collapse4" class="accordion-body collapse ">
            <div class="accordion-inner">
                <a href="http://dcastamo/Ventas/Ventas_Especificas/Yhum Harvy Alarcon Resumen Asesores.PDF" target="blank">
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/pdf.png"/><br>Resumen Asesores<br>
                </a>
            </div>
        </div>
    </div>

    <div class="accordion-group">
        <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse5">
                PUNTOS FIJOS
            </a>
        </div>
        <div id="collapse5" class="accordion-body collapse ">
            <div class="accordion-inner">
                <a href="http://dcastamo/Ventas/Ventas_Especificas/Resumen Asesores Puntos Fijos.PDF" target="blank">
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/pdf.png"/>Resumen Asesores Puntos Fijos
                </a>
            </div>
        </div>
    </div>

    <div class="accordion-group">
        <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse6">
                TAQUILLAS
            </a>
        </div>
        <div id="collapse6" class="accordion-body collapse ">
            <div class="accordion-inner">
                <a href="http://dcastamo/Ventas/Ventas_Especificas/Resumen Asesores Taquillas.PDF" target="blank">
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/pdf.png"/>Resumen Asesores Taquillas
                </a>
            </div>
        </div>
    </div>
</div>

