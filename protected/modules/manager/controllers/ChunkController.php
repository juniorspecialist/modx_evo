<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.09.14
 * Time: 14:48
 */

class ChunkController extends AdminController{

    public $layout='//layouts/column_manager';

    public function actionIndex(){

        $model = new Chunk('Search');

        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['Chunk'])){
            $model->attributes=$_GET['Chunk'];
        }

        $this->render('index', array('model'=>$model));
    }

    /*
     * добавление чанка
     */
    public function actionCreate(){

        $model = new Chunk();

        // collect user input data
        if(isset($_POST['Chunk']))
        {
            $model->attributes=$_POST['Chunk'];
            // validate params
            if($model->validate()){
                $model->save();
                Yii::app()->user->setFlash('msg','Успешно добавили новый чанк.');
                $this->redirect(array('chunk/index'));
            }
        }
        // display form
        $this->render('create',array('model'=>$model));
    }

    /*
     * редактирвоание чанка
     *
     */
    public function actionUpdate($id){

        $model = Chunk::model()->findBy_id($id);

        if($model&&isset($_POST['Chunk'])){

            $model->attributes = $_POST['Chunk'];

            if($model->validate()){

                $model->save();

                Yii::app()->user->setFlash('msg','Обновление чанка прошло успешно.');

                $this->redirect(array('chunk/index'));
                //$this->redirect(array('chunk/view','id'=>$id));
            }
        }

        $this->render('update',array('model'=>$model));
    }
} 