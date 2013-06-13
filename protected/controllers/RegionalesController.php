<?php

class RegionalesController extends Controller {

    public static function get_Ventas_CombinedColumn($ventasRegional) {
        //// ARRAY FECHAS PARA LAS CATEGORIAS
        $arrayVentasRegional = array();
        $fecha = "";
        foreach ($ventasRegional as $ventas) {
            if ($fecha != $ventas['FECHA_INGRESO']) {
                $fecha = $ventas['FECHA_INGRESO'];
                $arrayFechas[] = array('FECHA_INGRESO' => $fecha);
            }
        }

        /// ARRAY REGIONALES PARA GENERAR EL ARRAY FINAL DE LOS DATASET
        $arrayRegionales = array();
        $regional = "";
        foreach ($ventasRegional as $ventas) {
            if (!in_array(array('REGIONAL' => $ventas['REGIONAL']), $arrayRegionales)) {
                $regional = $ventas['REGIONAL'];
                $arrayRegionales[] = array('REGIONAL' => $regional);
            }
        }

        $array = array();
        $array3 = array();
        for ($i = 0; $i < Count($arrayRegionales); $i++) {
            $array[] = array('REGIONAL' => $arrayRegionales[$i]['REGIONAL']);
            $array2 = array();

            foreach ($ventasRegional as $venta) {
                if ($venta['REGIONAL'] == $arrayRegionales[$i]['REGIONAL'])
                    $array2[] = array('FECHA_INGRESO' => $venta['FECHA_INGRESO'], 'CANTIDAD' => $venta['CANTIDAD']);
            }
            $array3[] = array_merge(array('REGIONAL' => $arrayRegionales[$i]['REGIONAL']), $array2);
        }
        
        return $array3;
    }
}