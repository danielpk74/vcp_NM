<?php
$this->pageTitle = Yii::app()->name . " - Ventas Generales";
require_once ('/protected/components/FusionCharts.php');
?>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/filtros_ventas.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/filtros_ventas.js"></script>

<h5>Ventas Generales</h5>
<hr>

<?php
    echo CHtml::activeDropDownList($regionalesmodel, 'REGIONAL', CHtml::listData($regionales, 'CODIGO_REGIONAL_PK', 'REGIONAL'), array('name' => 'regionales', 'multiple' => 'multiple')); 
    echo CHtml::activeDropDownList($plazasmodel, 'PLAZA', CHtml::listData($plazas, 'PLAZA', 'PLAZA'), array('name' => 'plazas', 'multiple' => 'multiple')); 
    echo CHtml::activeDropDownList($productomodel, 'DESCRIPCION', CHtml::listData($productos, 'CODIGO_PRODUCTO_PK', 'DESCRIPCION'), array('name' => 'productos', 'multiple' => 'multiple'));
    echo CHtml::activeDropDownList($subProducto_, 'DESCRIPCION', CHtml::listData($subProductos, 'CODIGO_SUB_PRODUCTO_PK', 'DESCRIPCION'), array('name' => 'sub_productos', 'multiple' => 'multiple'));     
?>
<br><br>
<?php
    echo CHtml::activeDropDownList($uenmodel, 'DESCRIPCION', CHtml::listData($uens, 'CODIGO_UEN_PK', 'DESCRIPCION'), array('name' => 'uen', 'enabled' => false, 'multiple' => 'multiple'));

    $option = array('type' => 'POST',
        'url' => CController::createUrl('Site/Index'),
        'data' => array('producto' => "js:$('select#productos').val()", 'subproducto' => "js:$('select#sub_productos').val()", 'uen' => "js:$('select#uen').val()",'regional' => "js:$('select#regionales').val()",'plaza' => "js:$('select#plazas').val()"),
        'update' => '#detallesVentas',
        'success' => 'function(data) {
                                $(\'#detallesVentas\').html(data);
                            }');
    
    echo CHtml::ajaxButton('FILTRAR', CController::createUrl('Site/ventasGenerales'), $option, array('name' => 'btnDetallesVentas', 'class' => 'btn btn-mini')); 
?>
<hr>

<?php require_once ('ventasGeneralesMensuales.php'); ?>  