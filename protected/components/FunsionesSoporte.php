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
    
    public static function GenerarCategoryXMLChart($datos,$nombreCampo)
    {
        $strCategoryLabel = "<categories>";
//        $strCategoryLabel = "";
            foreach($datos as $dato)
            {
                  $strCategoryLabel .=" <category label='".$dato[$nombreCampo]."' /> ";          
            }
        $strCategoryLabel .="</categories>";            
        
        return $strCategoryLabel;
    }
    
     public static   function GenerarValueXMLChart($datos,$nombreDataSet,$nombreCampo)
    {
//         color='1D8BD1' anchorbordercolor='1D8BD1' anchorbgcolor='1D8BD1'
        $strSetValue = "<dataset seriesname='$nombreDataSet' >";
//        $strSetValue = "";
        foreach($datos as $dato)
        {
              $strSetValue .= "<set value='".$dato[$nombreCampo]."' />";
        } 
        $strSetValue .= "</dataset>";
        return $strSetValue;
    }


//    public static function GenerarXML_Chart()
    public static function GenerarXML_Chart($titulo,$subTitulo,$categorias,$dataSets,$TituloX,$TituloY)
    {
        $strXML .="<chart caption='$titulo' subcaption='$subTitulo' linethickness='1' showvalues='1' formatnumberscale='0' anchorradius='1' divlinealpha='20' divlinecolor='CC3300' divlineisdashed='1' showalternatehgridcolor='1' alternatehgridalpha='5' alternatehgridcolor='CC3300' shadowalpha='40' labelstep='1' numvdivlines='5' chartrightmargin='35' bgcolor='FFFFFF,CC3300' bgangle='270' bgalpha='10,10' decimalprecision='2' palette='1' xaxisname='Fecha de registro Monitoreo de novedades' yaxisname='Variaciones'>";
            // Categorias
            $strXML .=$categorias;
                
            // DataSets
            $strXML .= $dataSets;
            
            $strXML .= "<styles>";
                $strXML .="<definition>";
                    $strXML .="<style name='CaptionFont' type='font' size='12' />";
                    $strXML .="<style name='MyFirstGlow' type='Glow' color='FF5904' alpha='75' blurX='12' blurY='12'/>";
                $strXML .="</definition>";

                $strXML .="<application>";
                    $strXML .="<apply toObject='CAPTION' styles='CaptionFont' />";
                    $strXML .="<apply toObject='SUBCAPTION' styles='CaptionFont' />";
                $strXML .="</application>";
            $strXML .="</styles>";
        $strXML .="</chart>";
        
        return $strXML;
    }
}

?>
