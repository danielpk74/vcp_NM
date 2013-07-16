<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class SubProductos extends CFormModel {
    
    public $CODIGO_SUB_PRODUCTO_PK;
    public $DESCRIPCION;

    /**
     * Devuelve todos los subproductos de un producto enviado por parametro,
     * o todos los productos si no se envia parametros
     * @param type $subProductoID
     * @return array
     */
    public function get_SubProductos($subProductoID) {
      if ($subProductoID != '') {
            $subProductos = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('SUB_PRODUCTOS')
                    ->where("CODIGO_PRODUCTO_FK IN(SELECT * FROM FS_Split(:producto_id))", array(':producto_id' => $subProductoID))
                    ->order('DESCRIPCION ASC')
                    ->queryAll();
        } else {
            $subProductos = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('SUB_PRODUCTOS')
                    ->order('DESCRIPCION ASC')
                    ->queryAll();
        }

        return $subProductos;
    }
}
