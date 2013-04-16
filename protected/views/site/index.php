<?php
$this->pageTitle = Yii::app()->name;
require_once ('/protected/components/FusionCharts.php');
?>

<h4>Estado Actual de Ventas Diarias - <span id="producto" class="label label-important" style='font-size: 15px;'><?php echo CHtml::encode($producto) ?></span></h4>
<!--<div class="btn-group" style="float: right">
    <?php echo CHtml::linkButton('Filtrar', array('submit' => array('index', 'tid' => 'NUMMOV'))); ?>
    <button class="btn  " data-toggle="dropdown">Producto <span class="caret"></span></button>
    <ul class="dropdown-menu">
        <li><?php echo CHtml::linkButton('4G', array('submit' => array('index', 'tid' => 'NUMMOV'))); ?></li>
        <li><?php echo CHtml::linkButton('3G', array('submit' => array('index', 'tid' => 'INTMOV'))); ?></li>
    </ul>
</div>-->
<br>

<script type="text/javascript">
   $().ready(function() {
        $('#productos').live('change',function() {
            $.get("<?php echo CController::createUrl('Site/cargarSubProductos'); ?>",{producto :this.value, ajax: 'true'}, function(j){
                    $("select#sub_productos").empty();

                    select = $("select#sub_productos").get(0);
                    select.options[0] = new Option('Sub Producto', '');

                    $("select#sub_productos").append(j);
                 })
        });
   })
</script> 

<?php
echo CHtml::activeDropDownList($productomodel, 'DESCRIPCION', CHtml::listData($productos, 'PRODUCTO_ID_PK', 'DESCRIPCION'), array('name' => 'productos', 'prompt'=>'Producto',));
echo CHtml::activeDropDownList($subProducto_, 'DESCRIPCION', CHtml::listData($subProductos, 'SUB_PRODUCTO_ID_PK', 'DESCRIPCION'),  array('name' => 'sub_productos','prompt'=>'Sub Producto',));

$option = array(        'type'=>'POST',
                        'url' => CController::createUrl('Site/Index'),
                        'data'=>array('producto'=>'js:productos.value','subproducto'=>'js:sub_productos.value'),  
                        'update'=>'#detallesVentas', 
                        'success'=>'function(data) {
                            $(\'#detallesVentas\').html(data);
                        }');
echo CHtml::ajaxButton('FILTRAR', CController::createUrl('Site/Index'), $option, array('name'=>'actualizarDetallesVentas','class'=>'btn btn-small'));
?>
<hr>

<h5 style="text-align: right"><?php echo "Última Actualización " . $fechaactualizacion ?></h5>

<div id="detallesVentas"

<div id="filtro">
    <table  class="table table-striped table-bordered table-condensed"> 
        <tr>
            <th style='text-align: center'>PLAZA</th>
            <th style='text-align: center'>INGRESADAS</th>
            <th style='text-align: center'>INSTALADAS</th>                
        </tr>

        <?php foreach ($ventas as $venta) { ?>
            <tr>
                <td><?php echo CHtml::encode($venta['PLAZA']); ?></td>
                <td style='text-align: right'><?php echo CHtml::encode($venta['INGRESADAS']);
        $totalIngresadas += $venta['INGRESADAS'];
            ?></td>
                <td style='text-align: right'><?php echo CHtml::encode($venta['INSTALADAS']);
                $totalInstaladas += $venta['INSTALADAS'];
                ?></td>
            </tr>   
<?php } ?>

        <tfoot>
            <tr>
                <td class='td-footer'>Total </td>
                <td style='text-align: right'><?php echo CHtml::encode($totalIngresadas); ?></span></td>
                <td style='text-align: right'><?php echo CHtml::encode($totalInstaladas); ?></span></td>
            </tr>
            <tr>
                <td class='td-footer'>Total Mes Actual</td>
                <td style='text-align: right'><?php echo CHtml::encode($ingresadasMesActual); ?></span></td>
                <td style='text-align: right'><?php echo CHtml::encode($instaladasMesActual); ?></span></td>
            </tr>
            <tr>
                <td class='td-footer'><b>Pendientes Totales</td>
                <td style='text-align: right'><?php echo CHtml::encode($totalPendientes); ?></span></td>
                <td style='text-align: right'></td>
            </tr>
        </tfoot>
    </table>    
    
    <?php
    // Categoria de la grafica
    $categorias = FunsionesSoporte::GenerarCategoryXMLChart($ventasIngresadas, 'FECHA_INGRESO');

    // Dataset de la grafica
    $dataSets = FunsionesSoporte::GenerarValueXMLChart($ventasIngresadas, 'Ingresadas', 'TOTAL_INGRESADA');
    $dataSets .=FunsionesSoporte::GenerarValueXMLChart($ventasInstaladas, 'Instaladas', 'TOTAL_INSTALADA');

    $strXML = FunsionesSoporte::GenerarXML_Chart('Evolución Ventas Diarias', date('Y-m-d'), $categorias, $dataSets, "", "");
    echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/MSLine.swf", "", $strXML, "Vibraciones", "100%", 435, false, false) . "</center>";
    ?>
</div>