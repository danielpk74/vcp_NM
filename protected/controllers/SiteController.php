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

        $temporalVentas = new TemporalVentas();
        $this->ActualizarTemporal();

        $ventas = new Ventas();
        $ventasTotales = $ventas->Ingresadas(); //new CArrayDataProvider($ventas->Ingresadas(), array(
        
        $totalMesActual = $ventas->TotalIngrsadasMes();
        $totalPendientes = $ventas->Pendientes();
//        $ventasTotales = new CArrayDataProvider($ventas->Ingresadas(), array(
//            'id' => 'PLAZA',
//            'sort' => array(
//                'attributes' => array(
//                   'PLAZA', 'INGRESADAS', 'INSTALADAS'
//                ),
//            ),
//            'pagination' => array(
//                'pageSize' => 100,
//            ),
//        ));

        $ventas = new Ventas();
        $ventasIngresadas = $ventas->get_Ingresadas(7);
        $ventasInstaladas = $ventas->get_Instaladas(7);

        $this->render('index', array('ventas' => $ventasTotales, 
                                     'ventasIngresadas' => $ventasIngresadas, 
                                     'ventasInstaladas' => $ventasInstaladas));
    }
    
    
      /**
     * Actualiza la tabla temporal de ventas del dia
     * */
    public function ActualizarTemporal() {
        $temporalVentas = new TemporalVentas();
        $temporalVentas->TruncateTemporal();

        $ventas = new Ventas();

        $plazas = new Plazas();
        $plazas = $plazas->get_Plazas();

        foreach ($plazas as $plaza) {
            $ingresadas_plaza = $ventas->get_Ingresadas_Plaza_Fecha($plaza['PLAZA'], '2013-04-07');
            $instaladas_plaza = $ventas->get_Instaladas_Plaza_Fecha($plaza['PLAZA'], '2013-04-07');

            if (PlazasSeparadas::get_PlazaSeparada($plaza['PLAZA']))
                $ventas->set_Ingresadas_Instaladas($plaza['PLAZA'], $ingresadas_plaza['TOTAL_INGRESADA'], $instaladas_plaza['TOTAL_INSTALADA']);
            else
                $ventas->set_Ingresadas_Instaladas_Otros($ingresadas_plaza['TOTAL_INGRESADA'], $instaladas_plaza['TOTAL_INSTALADA']);
        }
    }
    

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

}