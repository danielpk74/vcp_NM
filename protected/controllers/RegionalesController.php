<?php

class RegionalesController extends Controller {
    
  
    /**
     * Genera el array necesario para crear la grafica de barras combinadas
     * @param array $ventasRegional Array con las ventas 
     * @param string $nombreCampo El nombre del campo key: FECHA_INGRESO/FECHA_INSTALACION
     * @param boolean $mensual Determina si la grafica a generar sera por dias o mensual
     * @param int $anio El año de la grafica a generar. El parametro es utilizado en el metodo get_CompletarMesesIntermedios para determinar cuantos meses al final de los datos debe completar,
     * Si el año es el actual, la grafica 
     * @return type
     */
    public static function get_Ventas_CombinedColumn($ventasRegional, $nombreCampo, $mensual = false,$anio) {
        $arrayRegionales = FunsionesSoporte::get_Regionales();
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

                $array2 = FunsionesSoporte::get_CompletarMesesIntermedios($array2, $array2, $nombreCampo,$anio);
            }

            $array3[] = array_merge(array('REGIONAL' => $arrayRegionales[$i]['REGIONAL']), $array2);
        }
        return $array3;
    }
}

