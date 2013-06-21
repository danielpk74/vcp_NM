<?php

class RegionalesController extends Controller {

    public static function get_Ventas_CombinedColumn($ventasRegional, $nombreCampo, $mensual = false) {
        //// ARRAY FECHAS PARA LAS CATEGORIAS
        $arrayVentasRegional = array();

        /// ARRAY REGIONALES PARA GENERAR EL ARRAY FINAL DE LOS DATASET
        $arrayRegionales = array();
        $regional = "";
        foreach ($ventasRegional as $ventas) {
            if (!in_array(array('REGIONAL' => $ventas['REGIONAL']), $arrayRegionales)) {
                $regional = $ventas['REGIONAL'];
                $arrayRegionales[] = array('REGIONAL' => $regional);
            }
        }

        sort($arrayRegionales);

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

            $array3[] = array_merge(array('REGIONAL' => $arrayRegionales[$i]['REGIONAL']), $array2);
        }

        return $array3;
    }
}