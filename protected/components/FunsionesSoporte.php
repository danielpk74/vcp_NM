<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

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
            $strCategoryLabel .=" <category label='" . $dato[$nombreCampo] . "' /> ";
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
    public static function GenerarValueXMLChart($datos, $nombreDataSet, $nombreCampo) {
//         color='1D8BD1' anchorbordercolor='1D8BD1' anchorbgcolor='1D8BD1'
        $strSetValue = "<dataset seriesname='$nombreDataSet' >";
//        $strSetValue = "";
        foreach ($datos as $dato) {
            $strSetValue .= "<set value='" . $dato[$nombreCampo] . "' />";
        }
        $strSetValue .= "</dataset>";
        return $strSetValue;
    }

    /**
     * Genera el string necesario para crear el grafico MSLine
     * @param type $titulo
     * @param type $subTitulo
     * @param type $categorias
     * @param type $dataSets
     * @param type $TituloX
     * @param type $TituloY
     * @return string
     */
    public static function GenerarXML_Chart($titulo, $subTitulo, $categorias, $dataSets, $TituloX = "", $TituloY = "") {
        $strXML .="<chart caption='$titulo' canvasborderalpha='0' subcaption='$subTitulo' linethickness='3' 
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

    public static function GenerarXML_AngularGauge() {

        $strXML = "<chart lowerLimit='0' upperLimit='100' lowerLimitDisplay='Bad' 
           upperLimitDisplay='Good' gaugeStartAngle='180' gaugeEndAngle='0'
           palette='1' numberSuffix='%' tickValueDistance='20' showValue='1' decimals='0' editMode='1'>
    
            <colorRange>
                <color minValue='0' maxValue='75' code='FF654F'/>
                <color minValue='75' maxValue='90' code='F6BD0F'/>
                <color minValue='90' maxValue='100' code='8BBA00'/>
            </colorRange>

            <dials>
                <dial id='CS' value='92' rearExtension='10'/>
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

}

?>
