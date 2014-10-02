<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.09.14
 * Time: 23:49
 */

class TvController extends AdminController{

    public $layout='//layouts/column_manager';

    /*
     * выводим список тв-параметров
     */
    public function actionIndex(){

        $model = new Tv('Search');

        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['Tv'])){
            $model->attributes=$_GET['Tv'];
        }


        $this->render('index', array('model'=>$model));
    }

    /*
     * редактирование тв-параметра
     */
    public function actionUpdate($id){

        $model = Tv::model()->findBy_id($id);

        if($model&&isset($_POST['Tv'])){

            $old_name_tv_param = $model->name;

            $model->attributes = $_POST['Tv'];

            if($model->validate()){

                //сперва мы отвязываем значение тв-паараметра со старым значением(вдруг изменили название тв-параметра)
                $tpl = new Template();
                $tpl->relationTvParamWithTemplates($old_name_tv_param, $_POST['access_tpl'], 'disconnection');

                //сохраняем новые или старые значения по тв-параметру
                $model->save();

                //обработка подвязок тв-параметра к указанным шаблонам
                //НО теперь делаем привязку параметра(тв) с его возможным изменённым именем(не РСУБД (()
                $tpl->relationTvParamWithTemplates($model->name, $_POST['access_tpl'], 'relation');

                Yii::app()->user->setFlash('msg','Обновление тв-параметра прошло успешно.');

                $this->redirect(array('tv/index'));
            }
        }

        $this->render('update',array('model'=>$model));
    }

    /*
     * добавим новый тв-параметр
     */
    public function actionCreate(){

        $model = new Tv();

        // collect user input data
        if(isset($_POST['Tv']))
        {
            $model->attributes=$_POST['Tv'];
            // validate params
            if($model->validate()){

                $model->save();

                // сохраним связи тв-параметра и выбранных шаблонов
                $tpl = new Template();
                $tpl->relationTvParamWithTemplates($model->name, $_POST['access_tpl'], 'relation');

                Yii::app()->user->setFlash('msg','Успешно добавили новый тв-параметр.');

                $this->redirect(array('tv/index'));
            }
        }
        // display form
        $this->render('create',array('model'=>$model));
    }

} 