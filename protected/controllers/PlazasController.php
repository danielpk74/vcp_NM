<?php

class PlazasController extends Controller {

    /**
     * Recibe array con los nombres de las plazas y quita la tilde.
     * @param array $plazas
     * @return array
     */
    public static function get_PlazasSinTilde($arrayPlazas)
    {
        foreach($arrayPlazas as $plaza)
        {
            $plazas[] = array('PLAZA'=>  FunsionesSoporte::QuitarAcentos($plaza['PLAZA']));
        }
        
        return $plazas;
    }
}