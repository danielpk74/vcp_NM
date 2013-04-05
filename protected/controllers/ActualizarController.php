<?php

class SiteController extends Controller {

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

        $this->render('index', array('ventas' => $ventasTotales,'ventasIngresadas'=>$ventasIngresadas,'ventasInstaladas'=>$ventasInstaladas));
    }
}