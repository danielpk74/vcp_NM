<?php

class Configuracion extends CFormModel {

    /**
     * Devuelve la fecha de la ultima actualizacion de los datos.
     * @return string Fecha de actualizacion
     */
    public static function get_FechaActualizacion() {
        $configuracion = Yii::app()->db->createCommand()
                ->select('FECHA_ACTUALIZACION')
                ->from('CONFIGURACION')
                ->queryScalar();

        return $configuracion;
    }
    
     /**
     * Devuelve la ultima fecha de actualizacion de la base de pedidos.
     * @return array
     */
    public static function get_HoraActualizacion() {
        $fechaActualizacion = Yii::app()->db->createCommand()
                            ->select('FECHA_ACTUALIZACION')
                            ->from('CONFIGURACION')
                            ->queryAll();
        
        return date('g',  $fechaActualizacion['FECHA_ACTUALIZACION']);
    }

    /**
     * Devuelve la ruta fisica donde se encuentras los archivos de los detalles
     * @return string Fecha de actualizacion
     */
    public function get_RutaInformes() {
        $configuracion = Yii::app()->db->createCommand()
                ->select('RUTA_INFORMES')
                ->from('CONFIGURACION')
                ->queryScalar();

        return $configuracion;
    }

}

?>
