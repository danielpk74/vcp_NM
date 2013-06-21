<?php
$this->pageTitle = Yii::app()->name;
require_once ('/protected/components/FusionCharts.php');
?>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/filtros_ventas.js"></script>


<h5>Estado Actual de Ventas - <?php echo "última Actualización " . date('d-m-Y h:i', strtotime($fechaactualizacion)) ?> </h5>
<hr>

<?php
    echo CHtml::activeDropDownList($productomodel, 'DESCRIPCION', CHtml::listData($productos, 'CODIGO_PRODUCTO_PK', 'DESCRIPCION'), array('name' => 'productos', 'multiple' => 'multiple'));
    echo CHtml::activeDropDownList($subProducto_, 'DESCRIPCION', CHtml::listData($subProductos, 'CODIGO_SUB_PRODUCTO_PK', 'DESCRIPCION'), array('name' => 'sub_productos', 'multiple' => 'multiple'));
?>

<?php
    echo CHtml::activeDropDownList($uenmodel, 'DESCRIPCION', CHtml::listData($uens, 'CODIGO_UEN_PK', 'DESCRIPCION'), array('name' => 'uen', 'enabled' => false, 'multiple' => 'multiple'));

    $option = array('type' => 'POST',
        'url' => CController::createUrl('Site/Index'),
        'data' => array('producto' => "js:$('select#productos').val()", 'subproducto' => "js:$('select#sub_productos').val()", 'uen' => "js:$('select#uen').val()", 'periodo' => 'js:cbo_periodo.value', 'fecha' => 'js:meses.value'),
        'update' => '#detallesVentas',
        'success' => 'function(data) {
                                $(\'#detallesVentas\').html(data);
                            }');
?>
<select id="cbo_periodo" name="cbo_periodo" >
    <option value="7">Ultimos 7 días</option>
    <option value="15" selected="true">Ultimos 15 días</option>
    <option value="30">Ultimos 30 días</option>
</select>

<!--<h5 style="text-align: right"><input type="text" id="fecha" class="span2" name="fecha" placeholder="Fecha Consulta" style="float: left"/> </h5>--> 

<select id="meses" name="meses">
    <option value="">Ninguno</option>
    <?php 
        foreach(FunsionesSoporte::get_NombreMes('', true) as $mes=>$value) {
            echo "<option value='$mes'>$value</option>";
        }
    ?>
</select>

<h5 style="text-align: right"><?php echo CHtml::ajaxButton('FILTRAR', CController::createUrl('Site/Index'), $option, array('name' => 'btnDetallesVentas', 'class' => 'btn btn-mini')); ?></h5>
<div id="detallesVentas" >    
    <?php require_once ('ventasGeneral.php'); ?>    
</div>

<!-- Modal -->
<div id="modalDetallesPlaza" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" ></div>
