<?php

class ActualizarController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $ventas = new Ventas();

        $ventasTotales = new CArrayDataProvider($ventas->Ingresadas(), array(
            'id' => 'PLAZA',
            'sort' => array(
                'attributes' => array(
                    'PLAZA', 'INGRESADAS', 'INSTALADAS'
                ),
            ),
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
        
         $ventas = new Ventas();
         $ventasIngresadas = $ventas->get_Ingresadas(1);
         $ventasInstaladas = $ventas->get_Instaladas(1);

        $this->render('indexs', array('ventas' => $ventasTotales,'ventasIngresadas'=>$ventasIngresadas,'ventasInstaladas'=>$ventasInstaladas));
    }
    
    
    /**
     * Actualiza la tabla temporal de ventas del dia
     * @param strign $fechaActualizacion La fecha de la ultima actualizacion de los datos
     * */
    public function ActualizarTemporal($fechaActualizacion) {
        if ($fechaActualizacion != date('Y-m-d')) {

            $tiposElementos = array('NUMMOV', 'INTMOV');
            
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
}