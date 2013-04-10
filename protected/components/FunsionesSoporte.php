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

    public static function GenerarCategoryXMLChart($datos, $nombreCampo) {
        $strCategoryLabel = "<categories>";
//        $strCategoryLabel = "";
        foreach ($datos as $dato) {
            $strCategoryLabel .=" <category label='" . $dato[$nombreCampo] . "' /> ";
        }
        $strCategoryLabel .="</categories>";

        return $strCategoryLabel;
    }

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

    public static function GenerarXML_Chart($titulo, $subTitulo, $categorias, $dataSets, $TituloX="", $TituloY="") {
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
        
//animation='1' showvalues='0' caption='' subcaption='' 
//       numberprefix='' chartrightmargin='15' xaxisname='' 
//       yaxisname='' pyaxisname=''  bgcolor='FFFFFF' 
//       bgratio='0' bgalpha='50' showalternatehgridcolor='0' numdivlines='0' 
//       showyaxisvalues='0' showlegend='0' chartleftmargin='35' charttopmargin='25'
//       chartbottommargin='35' drawanchors='0' bordercolor='970000' borderalpha='31' 
//       stack100percent='0' linethickness='3' basefontsize='12'        
        
//////////////// MS LINE
//        $strXML .= "<styles>";
//        $strXML .="<definition>";
//        $strXML .="<style name='CaptionFont' type='font' size='12' />";
//        $strXML .="<style name='MyFirstGlow' type='Glow' color='FF5904' alpha='75' blurX='12' blurY='12'/>";
//        $strXML .="</definition>";
//
//        $strXML .="<application>";
//        $strXML .="<apply toObject='CAPTION' styles='CaptionFont' />";
//        $strXML .="<apply toObject='SUBCAPTION' styles='CaptionFont' />";
//        $strXML .="</application>";
//        $strXML .="</styles>";
//        $strXML .="</chart>";
        
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
