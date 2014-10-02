<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.09.14
 * Time: 11:53
 */

class LoginController extends AdminController{

    public $defaultAction = 'login';

    public function actionLogin(){

        if(Yii::app()->user->isGuest){
            //$this->redirect('/manager/login');
            //$this->redirect(Yii::app()->controller->module->loginUrl);
        }else{
            //$this->redirect('/user/profile');
            $this->redirect(Yii::app()->controller->module->returnUrl);
        }

        $model=new LoginForm;

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login()){
                //после успешной атворизации оптравляем админа на главную страницу админки - дерево документов
                $this->redirect(Yii::app()->controller->module->returnUrl);
            }
                //$this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login',array('model'=>$model));
    }


    /**
     * Logout the current user and redirect to returnLogoutUrl.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        //$this->refresh();
        $this->redirect(Yii::app()->controller->module->returnLogoutUrl);
    }


} 