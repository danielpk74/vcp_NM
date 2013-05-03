<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = Yii::app()->name . ' - Error';
?>

<div class="alert alert-block alert-block fade in">
    <h4 class="alert-heading">Actualizando</h4>
    <br>
    <p><?php echo CHtml::encode($error); ?></p>
</div>    
<!--<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/actualizando.png" width="100" style="margin-top: 10px; float: right;position: absolute"/>-->

<a href="#" OnClick="javascript:$('#detalle').show('slow');">[+]</a>

<div id="detalle" class="alert alert-error alert-alert fade in" style="display: none">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <p><?php echo CHtml::encode($detalle); ?></p>
</div>

