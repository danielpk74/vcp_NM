<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="language" content="es"/>

        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico">

            <!-- blueprint CSS framework -->
            <!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />-->
            <!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />-->
            <!--[if lt IE 8]>
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
            <![endif]-->

<!--            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />-->
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-responsive.css" />
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css" />

            <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.9.1.js"></script>
            <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.js"></script>
            <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/fusionchart/FusionCharts.js"></script>
            <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>
        <div class="container" id="page">

            <div id="header">
                    <!--<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>-->
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logovcp.png" width="100%" style="margin-top: 10px;"/>
            </div><!-- header -->

            <div>
                <div class="navbar">
                    <div class="navbar-inner">
                        <div class="container">
                            <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </a>
                            <div class="nav-collapse collapse navbar-responsive-collapse">
                                <ul class="nav">
                                     <li><?php echo CHtml::linkButton('Inicio',array('submit'=>array('index')));?></li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Ventas <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                             <li><?php echo CHtml::linkButton('Presupuestos',array('submit'=>array('Presupuestos')));?></li>
                                             <li><?php echo CHtml::linkButton('Proyecciones','');?></li>
                                             <li><?php echo CHtml::linkButton('Cumplimientos','');?></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown" style="display: none">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pedidos <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Pendientes</a></li>
                                            <li><a href="#">Retiros</a></li>
                                        </ul>
                                    </li>
                                     
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Descargar <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a id='detalles' href="<?php echo Yii::app()->request->baseUrl; ?>../../archivosdetalles/DetallesMovilidad_4G_3G.xlsx"/>Detalles</a></li>
                                            <?php 
//                                               for($año=2012;$año<=date('Y');$año++)
//                                                   echo "<li><a id='$año' href='../movilidad/$año.xlsx'/>Detalles $año</a></li>" ;  
//                                          ?>
                                        </ul>
                                    </li>
                                    
                                    <li class="dropdown" style="display: none">
                                        <?php echo CHtml::linkButton('Actualizar',array('submit'=>array('Actualizar')));?>
                                        <ul class="dropdown-menu">
                                            <li></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div><!-- /.nav-collapse -->
                        </div>
                    </div><!-- /navbar-inner -->
                </div><!-- /navbar -->
                <?php
//                $this->widget('zii.widgets.CMenu', array(
//                    'items' => array(
//                        array('label' => 'Ventas', 'url' => array('/site/index')),
//                        array('label' => 'Retiros', 'url' => array('/site/page', 'view' => 'about')),
//                        array('label' => 'Anuladas', 'url' => array('/site/contact')),
//                        array('label' => 'Pendientes', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
//                        array('label' => 'Actualizar', 'url' => array('/site/page/administracion', 'view' => 'actualizar'), 'visible' => Yii::app()->user->isGuest),
////				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
//                    ),
//                ));
                ?>
            </div><!-- mainmenu -->

            <?php if (isset($this->breadcrumbs)): ?>
                <?php
                $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                ));
                ?><!-- breadcrumbs -->
            <?php endif ?>

            <?php echo $content; ?>

            <div class="clear"></div>

            <div id="footer">
                Vicepresidencia de Nuevos Mercados - UNE Telecomunicaciones.<br/>
                <?php echo Yii::powered(); ?> 
            </div><!-- footer -->

        </div><!-- page -->

    </body>
</html>
