<?php

class RegionalesController extends Controller {

    public static function get_Ventas_CombinedColumn($ventasRegional, $nombreCampo, $mensual = false) {
        $arrayRegionales = array(array('REGIONAL' => 'Centro'), array('REGIONAL' => 'NorOccidente'), array('REGIONAL' => 'Norte'), array('REGIONAL' => 'Sur'));
        $array3 = array();
        for ($i = 0; $i < Count($arrayRegionales); $i++) {
            $array2 = array();

            foreach ($ventasRegional as $venta) {
                if ($venta['REGIONAL'] == $arrayRegionales[$i]['REGIONAL'])
                    $array2[] = array("$nombreCampo" => $venta["$nombreCampo"], 'CANTIDAD' => $venta['CANTIDAD']);
            }

            // Pone cero en la regional cuyos ingresos o instalaciones sean cero siempre y cuando la consulta no sea por mes si no por dia.
            if (!$mensual) {
                if ($nombreCampo == 'FECHA_INGRESO')
                    $array2 = FunsionesSoporte::CompletarDias($array2, 2, 7, true);
                else
                    $array2 = FunsionesSoporte::CompletarDias($array2, 1, 7, true);
            }
            else {
                if ($array2[0]["$nombreCampo"] > 1)
                    array_unshift($array2, array("$nombreCampo" => ($array2[0]["$nombreCampo"] - 1), 'CANTIDAD' => '0'));
                if ($array2[0]["$nombreCampo"] > 1)
                    array_unshift($array2, array("$nombreCampo" => ($array2[0]["$nombreCampo"] - 1), 'CANTIDAD' => '0'));
                if ($array2[0]["$nombreCampo"] > 1)
                    array_unshift($array2, array("$nombreCampo" => ($array2[0]["$nombreCampo"] - 1), 'CANTIDAD' => '0'));
                if ($array2[0]["$nombreCampo"] > 1)
                    array_unshift($array2, array("$nombreCampo" => ($array2[0]["$nombreCampo"] - 1), 'CANTIDAD' => '0'));
                if ($array2[0]["$nombreCampo"] > 1)
                    array_unshift($array2, array("$nombreCampo" => ($array2[0]["$nombreCampo"] - 1), 'CANTIDAD' => '0'));
                if ($array2[0]["$nombreCampo"] > 1)
                    array_unshift($array2, array("$nombreCampo" => ($array2[0]["$nombreCampo"] - 1), 'CANTIDAD' => '0'));
                if ($array2[0]["$nombreCampo"] > 1)
                    array_unshift($array2, array("$nombreCampo" => ($array2[0]["$nombreCampo"] - 1), 'CANTIDAD' => '0'));

                $array2 = FunsionesSoporte::get_CompletarMesesIntermedios($array2, $array2, $nombreCampo);
            }

            $array3[] = array_merge(array('REGIONAL' => $arrayRegionales[$i]['REGIONAL']), $array2);
        }
        return $array3;
    }
}

