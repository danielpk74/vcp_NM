<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Productos extends CFormModel {

    public $CODIGO_PRODUCTO_PK;
    public $DESCRIPCION;

    /**
     * Consulta la tabla productos
     * @return type
     */
    public function get_Productos() {
        $productos = Yii::app()->db->createCommand()
                ->select('*')
                ->from('PRODUCTOS')
                ->order('DESCRIPCION ASC')
                ->queryAll();

        return $productos;
    }

    public function get_SubProductos($subProductoID = '') {
        if ($subProductoID != '') {
            $subProductos = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('SUB_PRODUCTOS')
                    ->where('CODIGO_PRODUCTO_FK=:producto_id', array(':producto_id' => $subProductoID))
                    ->queryAll();
        } else {
            $subProductos = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('SUB_PRODUCTOS')
                    ->queryAll();
        }

        return $subProductos;
    }

}
