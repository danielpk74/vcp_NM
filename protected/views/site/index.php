<?php
$this->pageTitle = Yii::app()->name;
require_once ('/protected/components/FusionCharts.php');
?>

<h4>Estado Actual de Ventas - <?php echo "última Actualización " . date('d-m-Y h:i', strtotime($fechaactualizacion)) ?> </h4>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.multiselect.js"></script>


<script type="text/javascript">
    $().ready(function() {
        $('#productos').multiselect({height: 140, minWidth: 190,noneSelectedText:'--Producto--'});
        $('#sub_productos').multiselect({height: 140, minWidth: 190,noneSelectedText:'--Sub Producto--'});
        $('#uen').multiselect({height: 140, minWidth: 190 ,noneSelectedText:'--UEN--'});
        $('#cbo_periodo').multiselect({
            multiple: false,
            header: false,
            height: 140,
            minWidth: 190,
            selectedList: 0,
            noneSelectedText:'--Periodo--'
        });

        $('#productos').live('change', function() {
            actualizarSelect("productos", "sub_productos");
        });
        
        jQuery('body').on('click', '#plaza', function() {
            jQuery.ajax({'type': 'POST', 'url': '<?php echo CController::createUrl('Site/DetallesPlaza'); ?>', 'data': {'plaza': this.text, 'cumplimiento': this.target}, 'success': function(data) {
                    $('#modalDetallesPlaza').html(data);
                }, 'cache': false});
        });

        $("body").on({
            ajaxStart: function() {
                $(this).addClass("loading");
            },
            ajaxStop: function() {
                $(this).removeClass("loading");
            }
        });
        
        $("select").multiselect();
    })

    function actualizarSelect(idSelectOrigen, idSelecDetino)
    {
        $.get("<?php echo CController::createUrl('Site/cargarSubProductos'); ?>", {producto: $('#productos').val(), ajax: 'true'}, function(j) {
            $('#' + idSelecDetino).multiselect("destroy");
            $("select#"+idSelecDetino).empty();
            $("select#"+idSelecDetino).append(j);
            $('#' + idSelecDetino).multiselect();

            if ($(idSelectOrigen).val() != "") {
                $('#' + idSelecDetino).multiselect("enable");
                $('#uen').multiselect("enable");
                $('#btnDetallesVentas').attr("disabled", false);
            }
            else {
                $('#sub_productos').attr("disabled", true);
                $('#cbo_periodo').attr("disabled", true);
                $('#uen').attr("disabled", true);
                $('#btnDetallesVentas').attr("disabled", true);
            }
        })
    }
</script>

<hr>
<?php
echo CHtml::activeDropDownList($productomodel, 'DESCRIPCION', CHtml::listData($productos, 'CODIGO_PRODUCTO_PK', 'DESCRIPCION'), array('name' => 'productos','multiple'=>'multiple'));
echo CHtml::activeDropDownList($subProducto_, 'DESCRIPCION', CHtml::listData($subProductos, 'CODIGO_SUB_PRODUCTO_PK', 'DESCRIPCION'), array('name' => 'sub_productos','multiple'=>'multiple'));
?>

<?php
echo CHtml::activeDropDownList($uenmodel, 'DESCRIPCION', CHtml::listData($uens, 'CODIGO_UEN_PK', 'DESCRIPCION'), array('name' => 'uen', 'enabled' => false,'multiple'=>'multiple'));

$option = array('type' => 'POST',
    'url' => CController::createUrl('Site/Index'),
    'data' => array('producto' => "js:$('select#productos').val()", 'subproducto' =>  "js:$('select#sub_productos').val()", 'uen' => "js:$('select#uen').val()", 'periodo' => 'js:cbo_periodo.value'),
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