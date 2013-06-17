<?php

/**
 * Description of FunsionesSoporte
 *
 * @author dmontol
 */
class FunsionesSoporte {

    ///////////////// MSLINE
    /**
     * Genera las categorias utilizadas en para los graficos Ejemplo
     * @param array $datos array que contiene los los nombres de las categorias
     * @param string $nombreCampo Los nombres de las etiquetas
     * @return string
     */
    public static function GenerarCategoryXMLChart($datos, $nombreCampo) {
        $strCategoryLabel = "<categories>";
        foreach ($datos as $dato) {
            if (is_array($dato))
                $strCategoryLabel .=" <category label='" . $dato[$nombreCampo] . "' /> ";
            else
                $strCategoryLabel .=" <category label='" . $dato . "' /> ";
        }
        $strCategoryLabel .="</categories>";

        return $strCategoryLabel;
    }

    /**
     * Genera el string con los dataset y los valores para cada dato que se quiere mostrar en el grafico
     * @param array $datos con los datos de los 
     * @param array $nombreDataSet dataset a Generar, ejemplo Dataset Miercoles-2012-01-01.
     * @param string $nombreCampo El nombre del campo que contiene el valor del DataSet, ejemplo el campo TOTAL_INGRESADAS => 20
     * @return string
     */
    public static function GenerarValueXMLChart($datos, $nombreDataSet, $nombreCampo, $line = false, $color = '') {
//       color='1D8BD1' anchorbordercolor='1D8BD1' anchorbgcolor='1D8BD1'

        if ($color != '')
            $color = "color='$color'";

        // Si el tipo de grafico es combined, 
        if ($line)
            $strSetValue = "<dataset seriesName='$nombreDataSet' renderAs='Line'>";
        else
            $strSetValue = "<dataset seriesname='$nombreDataSet' $color>";

        foreach ($datos as $dato) {
            $strSetValue .= "<set value='" . $dato[$nombreCampo] . "' />";
        }
        $strSetValue .= "</dataset>";
        return $strSetValue;
    }

    /**
     * Genera el string necesario para crear el grafico MSLine
     * @param string $titulo
     * @param string $subTitulo
     * @param array $categorias
     * @param array $dataSets
     * @param string $TituloX
     * @param string $TituloY
     * @return string XML para generar grafico FusionChart
     */
    public static function GenerarXML_ChartCombined($titulo, $subTitulo, $categorias, $dataSets, $TituloX = "", $TituloY = "") {
        $strXML .="<chart canvasBaseColor='D9E5F1' canvasBgColor='D9E5F1' showValues='0' caption='Total crimes for 2005-06' >";

        // Categorias
        $strXML .= $categorias;

        // DataSets
        $strXML .= $dataSets;

        $strXML .= "<styles>";
        $strXML .="<definition>";
        $strXML . "<style blurY='5' Alpha='50' Color='8F8F8F' Distance='8' name='Shadow_0' type='Shadow'/>";
        $strXML .="</definition>";

        $strXML .="<application>";
        $strXML .="<apply styles='Shadow_0' toObject='DATAPLOT'/>";
        $strXML .="</application>";
        $strXML .="</styles>";
        $strXML .="</chart>";

        return $strXML;
    }

    /**
     * Genera el string necesario para crear el grafico MSLine
     * @param string $titulo
     * @param string $subTitulo
     * @param array $categorias
     * @param array $dataSets
     * @param string $TituloX
     * @param string $TituloY
     * @return string XML para generar grafico FusionChart
     */
    public static function GenerarXML_ChartCombinedColumn($titulo, $subTitulo, $categorias, $dataSets) {
        $strXML .="<chart showValues='0' caption='$titulo' numberPrefix='' xAxisName='Dias' yAxisName='$subTitulo' useRoundEdges='1' >";

        // Categorias
        $strXML .= $categorias;

        // DataSets
        $strXML .= $dataSets;

        $strXML .="</chart>";

        return $strXML;
    }

    /**
     * Genera el string necesario para crear el grafico MSLine
     * @param string $titulo
     * @param string $subTitulo
     * @param array $categorias
     * @param array $dataSets
     * @param string $TituloX
     * @param string $TituloY
     * @return string XML para generar grafico FusionChart
     */
    public static function GenerarXML_Chart($titulo, $subTitulo, $categorias, $dataSets, $TituloX = "", $TituloY = "") {
        $strXML .="<chart palette='1' caption='$titulo' canvasborderalpha='0' subcaption='$subTitulo' linethickness='3' 
                   showvalues='1' formatnumberscale='0' anchorradius='3' divlinealpha='20' stack100percent='0'
                   divlinecolor='0' chartbottommargin='35' divlineisdashed='1' showalternatehgridcolor='0' 
                   alternatehgridalpha='0' alternatehgridcolor='CC3300' shadowalpha='40' labelstep='1' 
                   numvdivlines='0' chartrightmargin='35' bgcolor='FFFFFF,CC3300' bgangle='270' bgalpha='10,10' 
                   decimalprecision='2' palette='1' xaxisname='$TituloX' yaxisname='$TituloY'>";

        // Categorias
        $strXML .=$categorias;

        // DataSets
        $strXML .= $dataSets;

        $strXML .= "<styles>";
        $strXML .="<definition>";
        $strXML . "<style blurY='5' Alpha='50' Color='8F8F8F' Distance='8' name='Shadow_0' type='Shadow'/>";
        $strXML .="</definition>";

        $strXML .="<application>";
        $strXML .="<apply styles='Shadow_0' toObject='DATAPLOT'/>";
        $strXML .="</application>";
        $strXML .="</styles>";
        $strXML .="</chart>";

        return $strXML;
    }

    ///////////////////// ANGULAR GAUGE
    public function GenerarColorRangeXML($valorMinimo1 = 0, $valorMaximo1 = 75, $valorMinimo2 = 75, $valorMaximo2 = 90, $valorMinimo3 = 90, $valorMaximo3 = 100) {
        $strColorRange . "<colorRange>
                            <color minValue='$valorMinimo1' maxValue='$valorMaximo1' code='FF654F'/>
                            <color minValue='$valorMinimo2' $valorMaximo2='90' code='F6BD0F'/>
                            <color minValue='$valorMinimo3' maxValue='$valorMaximo3' code='8BBA00'/>
                         </colorRange>";

        return $strColorRange;
    }

    public function GenerarDialXML($diaID, $valor, $rearExtension = 10) {
        $strColorRange . "<dials>
                            <dial id='$diaID' value='$valor' rearExtension='$rearExtension'/>
                        </dials>";

        return $strColorRange;
    }

    public static function GenerarXML_Combi2D2($titulo, $categorias, $dataSets) {

        $strXML = "<chart palette='6' caption='$titulo' subCaption='' showValues='0' divLineDecimalPrecision='1' limitsDecimalPrecision='1' PYAxisName='Presupuesto' SYAxisName='' numberPrefix='' formatNumberScale='0' >";
        $strXML .= $categorias;
        $strXML .= $dataSets;
        $strXML .= "</chart>";

        return $strXML;
    }

    public static function GenerarXML_AngularGauge($valorMinimo = 0, $valorMaximo = 120, $valorDial = 0) {

        $strXML = "<chart lowerLimit='$valorMinimo' upperLimit='$valorMaximo' lowerLimitDisplay='$valorMinimo%' 
           upperLimitDisplay='$valorMaximo%' gaugeStartAngle='180' gaugeEndAngle='0'
           palette='1' numberSuffix='%' tickValueDistance='20' showValue='1' decimals='0' editMode='1'>
    
           <colorRange>
                <color minValue='0' maxValue='75' code='FF654F'/>
                <color minValue='75' maxValue='100' code='F6BD0F'/>
                <color minValue='100' maxValue='120' code='8BBA00'/>
            </colorRange>

            <dials>
                <dial id='CS' value='$valorDial' rearExtension='10'/>
            </dials>

            <styles>
                <definition>
                    <style type='font' name='myValueFont' bgColor='F1f1f1' borderColor='999999' />
                </definition>
                <application>
                    <apply toObject='Value' styles='myValueFont' />
                </application>
            </styles>
        </chart>";

        return $strXML;
    }

    /**
     * Quita las tildes de las cadenas de texto
     * @param type $incoming_string
     * @return type
     * @deprecated Este metodo debe eliminarse, el problema de los acentos debe ser corregido desde la configuracion
     * de la base de datos, se toma como medida provicional. 
     * since version 1
     */
    public static function QuitarAcentos($incoming_string) {
        $tofind = "ÀÁÂÄÅàáâäÒÓÔÖòóôöÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
        $replac = "AAAAAaaaaOOOOooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
        return strtr($incoming_string, utf8_decode($tofind), $replac);
    }

    /**
     * Devuelve el nombre del dia de la semana
     * @param date $fecha
     * @return string
     */
    public static function get_NombreDia($fecha) {
        $dia = date("N", strtotime($fecha));

        $dias = array('1' => 'Lun', '2' => 'Mar', '3' => 'Mie', '4' => 'Jue', '5' => 'Vie', '6' => 'Sab', '7' => 'Dom');

        $nombreDia = $dias[$dia];
        return $nombreDia;
    }

    /**
     * Devuelve el nombre del mes
     * @param date $fecha una fecha
     * @return string
     */
    public static function get_NombreMes($fecha, $todos = false) {
        if (!$todos)
            $mes = date("m", strtotime($fecha));

        $meses = array('01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
            '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');

        if (!$todos)
            return $meses[$mes];
        else
            return $meses;
    }

    /**
     * Determina el numero de dias que faltan para terminar el mes actual
     * se cuentan los festivos y los fines de semana juntos, pues tienen un comportamiento similiar.
     * @param integer $tipoDias Determina si se van a generar los dias habiles (1) o los dias festivos(2)
     * @return integer numero de dias faltantes.
     */
    public static function get_DiasFaltantesMes($tipoDias = 1) {
        $fecha = date('Y-m-d', strtotime("0 day", strtotime(date('Y-m-d'))));
        $mes = date('n', strtotime(date('Y-m-d')));
        $totalDiasHabilesFaltantes = 0;
        $totalDiasFestivosFaltantes = 0;

        while (date('n', strtotime($fecha)) <= $mes) {
            if (self::get_EsFestivo($fecha) || self::get_EsFinSemana($fecha))
                $totalDiasFestivosFaltantes++;
            else  // Habiles
                $totalDiasHabilesFaltantes++;

            $fecha = date('Y-m-d', strtotime("+1 day", strtotime($fecha)));
        }

        if ($tipoDias == 1) // Habiles
            return $totalDiasHabilesFaltantes;
        else// Festivos
            return $totalDiasFestivosFaltantes;
    }

    /**
     * Determina si una fecha es dia festivo
     * @param date $fecha Consulta si un dia especifico es o no festivo
     * @return boolean
     */
    public static function get_EsFestivo($fecha) {
        $festivo = Yii::app()->db->createCommand()
                ->select('*')
                ->from('DIAS_FESTIVO')
                ->where('FECHA IN(:fecha)', array('fecha' => $fecha))
                ->queryAll();

        if (Count($festivo) != 0)
            return true;
        else
            return false;
    }

    /**
     * Determina si una fecha es dia festivo
     * @param date $fecha
     * @return boolean
     */
    public static function get_EsFinSemana($fecha) {
        $date = date('N', mktime(0, 0, 0, date('n', strtotime($fecha)), date('d', strtotime($fecha)), date('Y', strtotime($fecha))));
        if ($date == 6 || $date == 7) {
            return true;
        }
        else
            return false;
    }

    /**
     * Completa los dias que faltan en un rango de fechas generado desde Sql server
     * @param array $datosInstalaciones array con los ingresos/retiros dados en un rango de tiempo
     * @param intger $opcion Determina con que valor se van a completar los: 1 Instaladas, 2 Ingresadas
     * @return array Total Array con la cantidad de dias a completar.
     */
    public static function CompletarDias($datosInstalaciones, $opcion, $numeroDias, $regional = false) {
        // Como sql server no muestra los dias en lo que no hubo ingresos/instalaciones, se crean los 15 dias por defecto
        // y se muestran en el grafico con valor cero
        $dias = array();

        if (date('A', strtotime(Configuracion::get_FechaActualizacion())) == 'AM' || date('Y-m-d', strtotime(Configuracion::get_FechaActualizacion())) != date('Y-m-d'))
            $hasta = 1;
        else
            $hasta = 0;

        for ($i = $numeroDias; $i >= $hasta; $i--)
            $dias[] = date('Y-m-d', strtotime("-$i day", strtotime(date('Y-m-d'))));

        if (!$regional) {
            if ($opcion == 1) {
                foreach ($dias as $fecha) {
                    $arrayIndex = 0;
                    $esta = false;
                    foreach ($datosInstalaciones as $dato) {
                        if ($fecha == $dato['FECHA_INSTALACION']) {
                            $cantidad = $dato['TOTAL_INSTALADA'];
                            $esta = true;
                        }

                        $arrayIndex++;
                    }
                    if ($esta) {
                        $Total[] = array('FECHA_INSTALACION' => self::get_NombreDia($fecha) . "-" . date('d-m', strtotime($fecha)), 'TOTAL_INSTALADA' => $cantidad);
                    } else {
                        $Total[] = array('TOTAL_INSTALADA' => self::get_NombreDia($fecha) . "-" . date('d-m', strtotime($fecha)), 'TOTAL_INSTALADA' => '0');
                    }
                }
            } else {
                foreach ($dias as $fecha) {
                    $arrayIndex = 0;
                    $esta = false;
                    foreach ($datosInstalaciones as $dato) {
                        if ($fecha == $dato['FECHA_INGRESO']) {
                            $cantidad = $dato['TOTAL_INGRESADA'];
                            $esta = true;
                        }

                        $arrayIndex++;
                    }
                    if ($esta) {
                        $Total[] = array('FECHA_INGRESO' => self::get_NombreDia($fecha) . "-" . date('d-m', strtotime($fecha)), 'TOTAL_INGRESADA' => $cantidad);
                    } else {
                        $Total[] = array('TOTAL_INGRESADA' => self::get_NombreDia($fecha) . "-" . date('d-m', strtotime($fecha)), 'TOTAL_INGRESADA' => '0');
                    }
                }
            }
        }
        
        // COMPLETAR LOS DIAS PARA EL GRAFICO POR REGIONAL
        else 
        {
            if ($opcion == 1) {
                foreach ($dias as $fecha) {
                    $arrayIndex = 0;
                    $esta = false;
                    foreach ($datosInstalaciones as $dato) {
                        if ($fecha == $dato['FECHA_INSTALACION']) {
                            $cantidad = $dato['CANTIDAD'];
                            $esta = true;
                        }

                        $arrayIndex++;
                    }
                    if ($esta) {
                        $Total[] = array('FECHA_INSTALACION' => self::get_NombreDia($fecha) . "-" . date('d-m', strtotime($fecha)), 'CANTIDAD' => $cantidad);
                    } else {
                        $Total[] = array('CANTIDAD' => self::get_NombreDia($fecha) . "-" . date('d-m', strtotime($fecha)), 'CANTIDAD' => '0');
                    }
                }
            } else {
                foreach ($dias as $fecha) {
                    $arrayIndex = 0;
                    $esta = false;
                    foreach ($datosInstalaciones as $dato) {
                        if ($fecha == $dato['FECHA_INGRESO']) {
                            $cantidad = $dato['CANTIDAD'];
                            $esta = true;
                        }

                        $arrayIndex++;
                    }
                    if ($esta) {
                        $Total[] = array('FECHA_INGRESO' => self::get_NombreDia($fecha) . "-" . date('d-m', strtotime($fecha)), 'CANTIDAD' => $cantidad);
                    } else {
                        $Total[] = array('CANTIDAD' => self::get_NombreDia($fecha) . "-" . date('d-m', strtotime($fecha)), 'CANTIDAD' => '0');
                    }
                }
            }
        }

        return $Total;
    }

    /**
     * Devuelve el presupuesto para una plaza.
     * @param string $plaza La plaza a consultar
     * @param string $uen 
     * @param string $tipoElemento Codigo con el que se encuentra en FENIX el producto de 4G O 3G, NUMMOV, LIMOV, TO, ETC
     * @param integer $anio Año del presupuesto que se desea consultar
     * @param integer $mes Mes del presupuesto que se desea consultar
     * @param type $consultaProducto Determina si se va a consultar un producto o un subproducto, en caso de ser producto el sistema buscara todos los registro de los subproductos pertenecientes al producto especificado.
     * @return array Presupuestos
     */
    public static function get_Presupuesto_X_Plaza($plaza, $uen, $tipoElemento, $anio, $mes, $consultaGeneral = '1', $consultaProducto = '') {
        $presupuesto = new Presupuestos();
        $cantidadPresupuesto = $presupuesto->get_Presupuesto($tipoElemento, $uen, $anio, $mes, $plaza, $consultaGeneral, $consultaProducto);

        return $cantidadPresupuesto;
    }

    /**
     * Devuelve el ultimo dia del mes consultado
     * @param int $mes el mes del que se desea obtener el ultimo dia
     * @param inte $anio el año del mes
     * @return integer el ultimo dia del mes en numero ejemplo 31
     */
    public static function get_Ultimo_dia_Mes($mes, $anio) {
        $mes = mktime(0, 0, 0, $mes, 1, $anio);
        setlocale('LC_ALL', 'co_CO');

        return intval(date("t", $mes));
    }

    public static function get_Porcentaje($numeroA, $numeroB, $numeroDecimales = 1) {
        if ($numeroB != 0)
            return number_format(($numeroA / $numeroB) * 100, $numeroDecimales, ',', '.');
        else
            return 0;
    }

    public static function get_ColoresUne($indice) {
//        $colores = array('189FA8','97949C','EBCE71','A6441E');
        $colores = array('36C0C7', 'D6D24D', '5AA348', 'A6441E');

        return $colores[$indice];
    }

}

?>
