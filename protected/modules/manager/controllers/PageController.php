<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11.09.14
 * Time: 22:31
 */
/*
 * CRUD операции по работе со страницами контента
 * model - Content
 */
class PageController extends AdminController {


    public $layout='//layouts/column_manager';

    public function actions()
    {
        return array(
            //Action-для обновления страницы контента как и из админки, так и при просмотре сайта, через диалоговое окно
            'updatePage'=>array(
                'class'=>'ext.actions.UpdatePageContentAction',
                'modelName'=>'Content',
            ),
        );
    }

    public function actionIndex(){

        $model = new Content('search');

        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['Content'])){
            $model->attributes=$_GET['Content'];
        }
        $this->render('index', array('model'=>$model));
    }

    /*
     * добавление страницы
     */
    public function actionCreate(){

        //подключаем класс для редактора контента
        Yii::import('ext.redactor.ImperaviRedactorWidget');

        $model = new Content();

        $template = Template::model()->findOne(array('_id'=>$model->template));

        // collect user input data
        if(isset($_POST['Content']))
        {
            $model->attributes=$_POST['Content'];

//            echo '<pre>';
//            print_r($model->attributes);
//            die();
            //возможно изменили шаблон, изменим набор тв-параметров у документа
            $template = Template::model()->findOne(array('_id'=>(string)$model->template));

            //echo '<pre>'; var_dump($template); die();
            // validate params
            if($model->validate()){

                //echo '<pre>'; print_r($_POST); die();

                //обработка тв-параметров
                if(isset($_POST['tv'])){
                    if(!empty($_POST['tv'])){
                        //фильтруем массив на пустые значения
                        $data_tv = array();
                        foreach($_POST['tv'] as $tv_name=>$tv_value){
                            if(!empty($tv_value)){
                                $data_tv[$tv_name] = $tv_value;
                            }
                        }
                        $model->tv = $data_tv;
                    }
                }

                $model->save();

                Yii::app()->user->setFlash('msg','Успешно добавили новый документ');

                $this->redirect(array('page/index'));
            }
        }

        //die('template='.YiiBase::app()->config->get('SYSTEM.DEFAULT_TEMPLATE'));
        //обработка подвязанны к шаблону тв-параметров и отображение их в форме
        $wizard = new ContentWizard($model, $template);
        $wizard->run();

        // display form
        $this->render('create',array('model'=>$model, 'tv'=>$wizard->tv));
    }

    /*
     * редактирвоание страницы
     *
     */
    public function actionUpdate($id){
        //подключаем класс для редактора контента
        Yii::import('ext.redactor.ImperaviRedactorWidget');

        $criteria = new EMongoCriteria(array(
            'condition' => array('_id'=>(int)$id),
        ));

        $model = Content::model()->findOne($criteria);

        if(isset($_POST['Content'])){
            $model->attributes=$_POST['Content'];
            //echo '<pre>'; print_r($_POST); die();

            if($model->validate()){

                //обработка тв-параметров
                if(isset($_POST['tv'])){
                    if(!empty($_POST['tv'])){
                        //фильтруем массив на пустые значения
                        $data_tv = array();
                        foreach($_POST['tv'] as $tv_name=>$tv_value){
                            if(!empty($tv_value)){
                                $data_tv[$tv_name] = $tv_value;
                            }
                        }
                        $model->tv = $data_tv;
                    }
                }

                $model->save();

                //обновление записи документа
                Yii::app()->user->setFlash('msg','Успешно обновили документ');

                $this->redirect(array('page/index'));
            }
        }

        //обработка подвязанны к шаблону тв-параметров и отображение их в форме
        $wizard = new ContentWizard($model, $model->tpl);
        $wizard->run();

        $this->render('update',array('model'=>$model, 'tv'=>$wizard->tv));
    }

    /*
     *
     */
} 