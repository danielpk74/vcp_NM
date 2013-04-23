<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class SubProductos extends CFormModel {
    
    public $SUB_PRODUCTO_ID_PK;
    public $DESCRIPCION;

    /**
     * Consulta la tabla productos
     * @return type
     */
    public function get_SubProductos($subProductoID) {
      if ($subProductoID != '') {
            $subProductos = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('SUB_PRODUCTOS')
                    ->where('PRODUCTO_ID_FK=:producto_id', array(':producto_id' => $subProductoID))
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
