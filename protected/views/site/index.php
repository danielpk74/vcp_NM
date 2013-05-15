<?php
$this->pageTitle = Yii::app()->name;
require_once ('/protected/components/FusionCharts.php');
?>

<h4>Estado Actual de Ventas - <?php echo "última Actualización " . date('d-m-Y h:i', strtotime($fechaactualizacion)) ?> </h4>
<script type="text/javascript">
    $().ready(function() {
        $('#productos').live('change', function() {
            $.get("<?php echo CController::createUrl('Site/cargarSubProductos'); ?>", {producto: this.value, ajax: 'true'}, function(j) {
                $("select#sub_productos").empty();

                select = $("select#sub_productos").get(0);
                select.options[0] = new Option('Sub Producto', '');

                $("select#sub_productos").append(j);

                if ($('#productos').val() != "") {
                    $('#sub_productos').attr("disabled", false);
                    $('#cbo_periodo').attr("disabled", false);
                    $('#uen').attr("disabled", false);
                    $('#btnDetallesVentas').attr("disabled", false);
                }
                else {
                    $('#sub_productos').attr("disabled", true);
                    $('#cbo_periodo').attr("disabled", true);
                    $('#uen').attr("disabled", true);
                    $('#btnDetallesVentas').attr("disabled", true);
                }
            })
        });

        $('#sub_productos').attr("disabled", true);
        $('#cbo_periodo').attr("disabled", true);
        $('#uen').attr("disabled", true);
        $('#btnDetallesVentas').attr("disabled", true);
        jQuery('body').on('click', '#plaza', function() {
            //this.parentNode.parentNode this.parentNode.getElementById('tdcumplimiento').firstChild
            //alert(this.parentNode.parentNode.lastChild.);
            jQuery.ajax({'type': 'POST', 'url': '<?php echo CController::createUrl('Site/DetallesPlaza'); ?>', 'data': {'plaza': this.text, 'cumplimiento': this.target}, 'success': function(data) {
                    $('#modalDetallesPlaza').html(data);
                }, 'cache': false});
        });

//        $('#uen').multiselect();

        $("body").on({
            ajaxStart: function() {
                $(this).addClass("loading");
            },
            ajaxStop: function() {
                $(this).removeClass("loading");
            }
        });
    })
</script>

<hr>

<?php
echo CHtml::activeDropDownList($productomodel, 'DESCRIPCION', CHtml::listData($productos, 'CODIGO_PRODUCTO_PK', 'DESCRIPCION'), array('name' => 'productos', 'prompt' => 'Producto', 'class' => 'select'));
echo CHtml::activeDropDownList($subProducto_, 'DESCRIPCION', CHtml::listData($subProductos, 'CODIGO_SUB_PRODUCTO_PK', 'DESCRIPCION'), array('name' => 'sub_productos', 'prompt' => 'Sub Producto',));
echo CHtml::activeDropDownList($uenmodel, 'DESCRIPCION', CHtml::listData($uens, 'CODIGO_UEN_PK', 'DESCRIPCION'), array('name' => 'uen', 'prompt' => 'UEN', 'enabled' => false));

$option = array('type' => 'POST',
    'url' => CController::createUrl('Site/Index'),
    'data' => array('producto' => 'js:productos.value', 'subproducto' => 'js:sub_productos.value', 'uen' => 'js:uen.value', 'periodo' => 'js:cbo_periodo.value'),
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

<?php echo CHtml::ajaxButton('FILTRAR', CController::createUrl('Site/Index'), $option, array('name' => 'btnDetallesVentas', 'class' => 'btn btn-mini')); ?>

<div id="detallesVentas" >    
    <h5 style="text-align: right"></h5>
    <?php require_once ('/plantillas/ventasGeneral.php'); ?>    
</div>

<!-- Modal -->
<div id="modalDetallesPlaza" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" ></div>