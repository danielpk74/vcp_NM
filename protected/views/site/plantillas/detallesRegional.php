<!--<h4><?php echo $opcion; ?> Por Regional</h4>-->
<!--<hr>-->


<!-- COMIENZA INGRESOS POR REGIONAL -->
<div class="tabbable"> <!-- Only required for left/right tabs -->
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab">Ingresadas</a></li>
        <li><a href="#tab2" data-toggle="tab">Instaladas</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab1">
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
        </div>
        
        <div class="tab-pane" id="tab2">
            <?php
            $categorias = FunsionesSoporte::GenerarCategoryXMLChart($fechasInstalaciones, 'FECHA_INSTALACION');
                        
            $dataSets = "";
            for ($i = 0; $i < Count($instaladasRegional); $i++) {
                $regional = $instaladasRegional[$i]['REGIONAL'];
                unset($instaladasRegional[$i]['REGIONAL']);
                $dataSets .= FunsionesSoporte::GenerarValueXMLChart($instaladasRegional[$i], $regional, 'CANTIDAD', false);
            }
            
            $strXML3 = FunsionesSoporte::GenerarXML_ChartCombinedColumn('Instaladas X Regional', 'Cantidad Instalada', $categorias, $dataSets);
            
            
            $fp = fopen('data.txt', 'w');
            fwrite($fp, $strXML3);
            fclose($fp);
            
            echo "<center>" . renderChart(Yii::app()->request->baseUrl . "/utilidades/fusionchart/StackedColumn2DLine.swf", "", $strXML3, "InstalacionRegional", '100%', 250, false, true) . "</center>";
            ?>
        </div>
    </div>
</div>