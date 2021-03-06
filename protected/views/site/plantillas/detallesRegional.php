<?php 
  //TODO Cambiar
   $fechas = array(
                array('MES' => 'Enero'),
                array('MES' => 'Febrero'),
                array('MES' => 'Marzo'),
                array('MES' => 'Abril'),
                array('MES' => 'Mayo'),
                array('MES' => 'Junio'),
                array('MES' => 'Julio'),
                array('MES' => 'Agosto'),
                array('MES' => 'Septiembre'),
                array('MES' => 'Octubre'),
                array('MES' => 'Noviembre'),
                array('MES' => 'Diciembre'));
?>
<!-- COMIENZA INGRESOS POR REGIONAL -->
<div class="tabbable"> 
    <ul class="nav nav-tabs">
        <li class="active"><a href="#ingresadas" data-toggle="tab">Ingresadas</a></li>
        <li><a href="#instaladas" data-toggle="tab">Instaladas</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="ingresadas">
            <?php if ($fechasIngresos != null) { ?>
                <!--- INGRESADAS  X DIAS X REGIONAL-->
                <h5>Evolución últimos 7 días X Regional</h5>
                <?php
                $categorias = FunsionesSoporte::GenerarCategoryXMLChart($fechasIngresos, 'FECHA_INGRESO');

                $dataSets = "";
                for ($i = 0; $i < Count($ingresadasRegional); $i++) {
                    $regional = $ingresadasRegional[$i]['REGIONAL'];
                    unset($ingresadasRegional[$i]['REGIONAL']);
                    $dataSets .= FunsionesSoporte::GenerarValueXMLChart($ingresadasRegional[$i], $regional, 'CANTIDAD', false);
                }

                $strXML3 = FunsionesSoporte::GenerarXML_ChartCombinedColumn('Ingresos X Regional', 'Cantidad Ingresada', $categorias, $dataSets);
                echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/StackedColumn2DLine.swf", "", $strXML3, "IngresosRegional", '100%', 250, false, true) . "</center>";
                ?>

                <hr>

            <?php } ?>

            <!--Ingresadas X Mes X Regional-->
            <h5>Ingresadas X Mes X Regional</h5>

            <?php
            
            $categorias = FunsionesSoporte::GenerarCategoryXMLChart($fechas, 'MES');

            $dataSets = "";
            for ($i = 0; $i < Count($ingresadasRegionalxMes); $i++) {
                $regional = $ingresadasRegionalxMes[$i]['REGIONAL'];
                unset($ingresadasRegionalxMes[$i]['REGIONAL']);
                $dataSets .= FunsionesSoporte::GenerarValueXMLChart($ingresadasRegionalxMes[$i], $regional, 'CANTIDAD', false);
            }

            $strXML3 = FunsionesSoporte::GenerarXML_ChartCombinedColumn('Ingresadas x Regional x Mes', 'Cantidad Ingresada', $categorias, $dataSets);
            echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/StackedColumn2DLine.swf", "", $strXML3, "IngresadasRegionalxMes", '100%', 250, false, true) . "</center>";
            ?>

            <!--- FIN INGRESADAS -->
        </div>

        <div class="tab-pane" id="instaladas">

            <?php if ($fechasIngresos != null) { ?>
                <!--- INSTALADAS  X DIAS X REGIONAL-->
                <h5>Evolución últimos 7 días X Regional</h5>
                <?php
                $categorias = FunsionesSoporte::GenerarCategoryXMLChart($fechasInstalaciones, 'FECHA_INSTALACION');

                $dataSets = "";
                for ($i = 0; $i < Count($instaladasRegional); $i++) {
                    $regional = $instaladasRegional[$i]['REGIONAL'];
                    unset($instaladasRegional[$i]['REGIONAL']);
                    $dataSets .= FunsionesSoporte::GenerarValueXMLChart($instaladasRegional[$i], $regional, 'CANTIDAD', false);
                }

                $strXML3 = FunsionesSoporte::GenerarXML_ChartCombinedColumn('Instaladas X Regional', 'Cantidad Instalada', $categorias, $dataSets);

                echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/StackedColumn2DLine.swf", "", $strXML3, "InstalacionRegional", '100%', 250, false, true) . "</center>";
                ?>

                <hr> 

            <?php } ?>

            <!--Instaladas X Mes X Regional-->
            <h5>Instaladas X Mes X Regional</h5>

            <?php
            $categorias = FunsionesSoporte::GenerarCategoryXMLChart($fechas, 'MES');

            $dataSets = "";
            for ($i = 0; $i < Count($instaladasRegionalxMes); $i++) {
                $regional = $instaladasRegionalxMes[$i]['REGIONAL'];
                unset($instaladasRegionalxMes[$i]['REGIONAL']);
                $dataSets .= FunsionesSoporte::GenerarValueXMLChart($instaladasRegionalxMes[$i], $regional, 'CANTIDAD', false);
            }

            $strXML3 = FunsionesSoporte::GenerarXML_ChartCombinedColumn('Instaladas x Regional x Mes', 'Cantidad Instalada', $categorias, $dataSets);
            echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/StackedColumn2DLine.swf", "", $strXML3, "InstalacionesRegionalxMes", '100%', 250, false, true) . "</center>";
            ?>

            <!--- FIN INSTALADAS-->
        </div>
    </div>
</div>