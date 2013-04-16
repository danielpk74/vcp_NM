<?php

class Configuracion extends CFormModel {

    /**
     * Devuelve la fecha de la ultima actualizacion de los datos.
     * @return string Fecha de actualizacion
     */
    public function get_FechaActualizacion() {
        $configuracion = Yii::app()->db->createCommand()
                ->select('FECHA_ACTUALIZACION')
                ->from('CONFIGURACION')
                ->queryScalar();

        return $configuracion;
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
