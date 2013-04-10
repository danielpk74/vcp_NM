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

        $producto = "4G";
        
        // Definimos el tipo elemento a consultar, si no esta dado por get,  asume 4G por defecto
        if (isset($_GET['tid']))  {
            $this->setPageState('tipo_elemento',$_GET['tid']);
            
            if($_GET['tid'] != 'NUMMOV')
                $producto = "3G";
        }
        else 
            $this->setPageState('tipo_elemento','NUMMOV');
        
        $temporalVentas = new TemporalVentas();
        $this->ActualizarTemporal();

        $ventas = new Ventas();
        $ventasTotales = $ventas->Ingresadas();
        $totalIngresadasMesActual = $ventas->TotalIngresadasMes($this->getPageState('tipo_elemento'));
        $totalInstaladasMesActual = $ventas->TotalInstaladasMes($this->getPageState('tipo_elemento'));
        $totalPendientes = $ventas->TotalPendientes($this->getPageState('tipo_elemento'));
        
        $ventas = new Ventas();
        $ventasIngresadas = $ventas->get_Ingresadas(7,$this->getPageState('tipo_elemento'));
        $ventasInstaladas = $ventas->get_Instaladas(7,$this->getPageState('tipo_elemento'));

        $this->render('index', array('producto'=>$producto,
            'ventas' => $ventasTotales,
            'ventasIngresadas' => $ventasIngresadas,
            'ingresadasMesActual' => $totalIngresadasMesActual,
            'instaladasMesActual' => $totalInstaladasMesActual,
            'totalPendientes' => $totalPendientes,
            'ventasInstaladas' => $ventasInstaladas));
    }

    /**
     * Actualiza la tabla temporal de ventas del dia
     * */
    public function ActualizarTemporal() {
        $temporalVentas = new TemporalVentas();
        $temporalVentas->TruncateTemporal();

        $plazas = new Plazas();
        $plazas = $plazas->get_Plazas();

        $ingresadasOtros = 0;
        $instaladasOtros = 0;

        $ventas = new Ventas();

        $ayer = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d'))));
        foreach ($plazas as $plaza) {
            $ingresadasPlaza = $ventas->get_Ingresadas_Plaza_Fecha($plaza['PLAZA'], $ayer,$this->GetPageState('tipo_elemento'));
            $instaladasPlaza = $ventas->get_Instaladas_Plaza_Fecha($plaza['PLAZA'], $ayer,$this->GetPageState('tipo_elemento'));

            if (PlazasSeparadas::get_PlazaSeparada($plaza['PLAZA'])) {
                $ventas->set_Ingresadas_Instaladas(FunsionesSoporte::QuitarAcentos($plaza['PLAZA']), $ingresadasPlaza['TOTAL_INGRESADA'], $instaladasPlaza['TOTAL_INSTALADA']);
            } else {
                $ingresadasOtros += $ingresadasPlaza['TOTAL_INGRESADA'];
                $instaladasOtros += $instaladasPlaza['TOTAL_INSTALADA'];
            }
        }

        $ventas->set_Ingresadas_Instaladas_Otros($ingresadasOtros, $instaladasOtros);
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