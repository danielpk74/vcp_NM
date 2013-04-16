<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Actualizar extends CFormModel {

    /**
     * Actualiza la tabla temporal de ventas del dia
     * */
    public function ActualizarTemporal($fechaActualizacion) {
        if ($fechaActualizacion != date('Y-m-d')) {

            $tiposElementos = array('NUMMOV', 'INTMOV','LIMOV');
            
            $temporalVentas = new TemporalVentas();
            $temporalVentas->TruncateTemporal();

            for ($i = 0; $i < Count($tiposElementos); $i++) {
                
                $tipoElemento = $tiposElementos[$i];

                $plazas = new Plazas();
                $plazas = $plazas->get_Plazas();

                $ingresadasOtros = 0;
                $instaladasOtros = 0;

                $ventas = new Ventas();

                if(date('H') < '12')
                    $ayer = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d'))));
                else
                    $ayer = date('Y-m-d');
                
                foreach ($plazas as $plaza) {
                    $ingresadasPlaza = $ventas->get_Ingresadas_Plaza_Fecha($plaza['PLAZA'], $ayer, $tipoElemento);
                    $instaladasPlaza = $ventas->get_Instaladas_Plaza_Fecha($plaza['PLAZA'], $ayer, $tipoElemento);

                    if (PlazasSeparadas::get_PlazaSeparada($plaza['PLAZA'])) {
                        $ventas->set_Ingresadas_Instaladas(FunsionesSoporte::QuitarAcentos($plaza['PLAZA']), $ingresadasPlaza['TOTAL_INGRESADA'], $instaladasPlaza['TOTAL_INSTALADA'], $tipoElemento);
                    } else {
                        $ingresadasOtros += $ingresadasPlaza['TOTAL_INGRESADA'];
                        $instaladasOtros += $instaladasPlaza['TOTAL_INSTALADA'];
                    }
                }

                $ventas->set_Ingresadas_Instaladas_Otros($ingresadasOtros, $instaladasOtros, $tipoElemento);
            }

            $this->ActualizarFecha();
        }

        return true;
    }

    /**
     * Actualiza el campo de la fecha de la ultima carga de informacion
     */
    public function ActualizarFecha() {
        Yii::app()->db->createCommand()->update('CONFIGURACION', array(
            'FECHA_ACTUALIZACION' => date('Y-m-d'),
                ), 'CONFIGURACION_ID=:id', array(':id' => '1'));
    }

}
