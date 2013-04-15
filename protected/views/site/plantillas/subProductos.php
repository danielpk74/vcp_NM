<?php
 echo CHtml::activeDropDownList($subProducto_, 'DESCRIPCION', CHtml::listData($subProductos, 'SUB_PRODUCTO_ID_PK', 'DESCRIPCION'), $options2)    
?>
