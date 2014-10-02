<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16.09.14
 * Time: 21:49
 */

class TemplateController extends AdminController {

    public $layout='//layouts/column_manager';

    /*
     * выводим список шаблонов
     */
    public function actionIndex(){

        $model = new Template('Search');

        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['Template'])){
            $model->attributes=$_GET['Template'];
        }

        $this->render('index', array('model'=>$model));
    }

    /*
      * добавление шаблона
      */
    public function actionCreate(){

        $model = new Template();

        // collect user input data
        if(isset($_POST['Template']))
        {
            $model->attributes=$_POST['Template'];

            // validate params
            if($model->validate()){
                $model->save();
                $this->redirect(array('template/index'));
            }
        }
        // display form
        $this->render('create',array('model'=>$model));
    }

    /*
     * редактирвоание шаблона
     *
     */
    public function actionUpdate($id){

        $model = $this->loadModel($id);

        if($model&&isset($_POST['Template'])){

            $model->attributes = $_POST['Template'];

            if($model->validate()){

                $model->save();

                $this->redirect(array('template/index'));
            }
        }

        $this->render('update',array('model'=>$model));
    }

    public function loadModel($id){
        $criteria = new EMongoCriteria(array(
            'condition' => array('_id'=>trim($id)),
        ));
        $model = Template::model()->findOne(array('_id'=>$id));

        if($model==null){
            throw new CHttpException(404,'The requested page does not exist.');
        }

        return $model;
    }
} 