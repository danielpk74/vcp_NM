<?php
$this->pageTitle = Yii::app()->name;
require_once ('/protected/components/FusionCharts.php');
?>

<h4>Estado Actual de Ventas Diarias - <?php echo "última Actualización " . date('d-m-Y h:i', strtotime($fechaactualizacion)) ?> </h4>
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
                    $('#uen').attr("disabled", false);
                    $('#btnDetallesVentas').attr("disabled", false);
                }
                else {
                    $('#sub_productos').attr("disabled", true);
                    $('#uen').attr("disabled", true);
                    $('#btnDetallesVentas').attr("disabled", true);
                }
            })
        });

        $('#sub_productos').attr("disabled", true);
        $('#uen').attr("disabled", true);
        $('#btnDetallesVentas').attr("disabled", true);
    })
</script>

<hr>

<?php
echo CHtml::activeDropDownList($productomodel, 'DESCRIPCION', CHtml::listData($productos, 'CODIGO_PRODUCTO_PK', 'DESCRIPCION'), array('name' => 'productos', 'prompt' => 'Producto', 'class' => 'select'));
echo CHtml::activeDropDownList($subProducto_, 'DESCRIPCION', CHtml::listData($subProductos, 'CODIGO_SUB_PRODUCTO_PK', 'DESCRIPCION'), array('name' => 'sub_productos', 'prompt' => 'Sub Producto',));
echo CHtml::activeDropDownList($uenmodel, 'DESCRIPCION', CHtml::listData($uens, 'CODIGO_UEN_PK', 'DESCRIPCION'), array('name' => 'uen', 'prompt' => 'UEN', 'enabled' => false));

$option = array('type' => 'POST',
    'url' => CController::createUrl('Site/Index'),
    'data' => array('producto' => 'js:productos.value', 'subproducto' => 'js:sub_productos.value', 'uen' => 'js:uen.value'),
    'update' => '#detallesVentas',
    'success' => 'function(data) {
                                $(\'#detallesVentas\').html(data);
                            }');
echo CHtml::ajaxButton('FILTRAR', CController::createUrl('Site/Index'), $option, array('name' => 'btnDetallesVentas', 'class' => 'btn btn-mini'));
?>

<h5 style="text-align: right"></h5>

<div id="detallesVentas">

    <div id="filtro">
        <hr>
        <h5  style="text-align: right">Ingresos/Instalaciones del día: <?php echo date('d-m-Y', strtotime($fechaConsulta)) ?></h5>
        <table  class="table table-striped table-bordered table-condensed"> 
            <tr>
                <th style='text-align: center'>PLAZA</th>
                <th style='text-align: center'>INGRESADAS</th>
                <th style='text-align: center'>INSTALADAS</th>                
            </tr>

            <?php foreach ($ventas as $venta) { ?>
                <tr>
                    <td>
                        <?php echo FunsionesSoporte::QuitarAcentos($venta['PLAZA']); ?></td>
                    <td style='text-align: right'><?php
                        echo CHtml::encode($venta['INGRESADAS']);
                        $totalIngresadas += $venta['INGRESADAS'];
                        ?>
                    </td>
                    <td style='text-align: right'><?php
                        echo CHtml::encode($venta['INSTALADAS']);
                        $totalInstaladas += $venta['INSTALADAS'];
                        ?></td>
                </tr>   
            <?php } ?>

            <?php foreach ($ventasOtros as $venta) { ?>
                <tr>
                    <td><?php echo FunsionesSoporte::QuitarAcentos($venta['PLAZA']); ?></td>
                    <td style='text-align: right'><?php
                        echo CHtml::encode($venta['INGRESADAS']);
                        $totalIngresadas += $venta['INGRESADAS'];
                        ?></td>
                    <td style='text-align: right'><?php
                        echo CHtml::encode($venta['INSTALADAS']);
                        $totalInstaladas += $venta['INSTALADAS'];
                        ?></td>
                </tr>   
            <?php } ?>

            <tfoot>
                <tr>
                    <td class='td-footer'>Total </td>
                    <td style='text-align: right'><span class="label label-important"><?php echo CHtml::encode($totalIngresadas); ?></span></td>
                    <td style='text-align: right'><span class="label label-important"><?php echo CHtml::encode($totalInstaladas); ?></span></td>
                </tr>
                <tr>
                    <td class='td-footer'>Total Mes de <?php echo FunsionesSoporte::get_NombreMes(date('Y-m-d')) ?></td>
                    <td style='text-align: right'><span class="label label-important"><?php echo CHtml::encode($ingresadasMesActual); ?></span></td>
                    <td style='text-align: right'><span class="label label-important"><?php echo CHtml::encode($instaladasMesActual); ?></span></td>
                </tr>
                
                <tr>
                    <td class='td-footer'>Proyectado Mes <?php echo FunsionesSoporte::get_NombreMes(date('Y-m-d')) ?></td>
                    <td style='text-align: right'><span class="label label-important"></span></td>
                    <td style='text-align: right'><span class="label label-important"><?php echo $proyectadoMesActual ?></span></td>
                </tr>

                <tr>
                    <td class='td-footer'><b>Pendientes Totales</td>
                    <td style='text-align: right'><span class="label label-important"><?php echo CHtml::encode($totalPendientes); ?></span></td>
                    <td style='text-align: right'></td>
                </tr>
            </tfoot>
        </table>    

        <hr>

        <?php
        // Categoria de la grafica
        $categorias = FunsionesSoporte::GenerarCategoryXMLChart($ventasIngresadas, 'FECHA_INGRESO');

        // Dataset de la grafica
        $dataSets = FunsionesSoporte::GenerarValueXMLChart($ventasIngresadas, 'Ingresadas', 'TOTAL_INGRESADA');
        $dataSets .=FunsionesSoporte::GenerarValueXMLChart($ventasInstaladas, 'Instaladas', 'TOTAL_INSTALADA');

        $strXML = FunsionesSoporte::GenerarXML_Chart('Evolución Ventas', 'Últimos 15 días Hasta el ' . date('d-m-Y h:i', strtotime($fechaactualizacion)), $categorias, $dataSets, "", "");
        echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/MSLine.swf", "", $strXML, "Ventas", "100%", 435, false, false) . "</center>";
        ?>

    </div>