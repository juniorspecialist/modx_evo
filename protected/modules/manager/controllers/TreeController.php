<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.09.14
 * Time: 13:24
 */

/*
 * операции для работы с докумекнтами :
 *  вывод списка доков в виде дерева
 *  добавление,редактирвоание доков, удаление, поиск, просмотр
 */
class TreeController extends AdminController {

    public $layout='//layouts/column_admin_tree';

    public function actions()
    {
        return array(
            'fillTree'=>array(
                'class'=>'ext.actions.XFillTreeAction',
                'modelName'=>'Content',
                'showRoot'=>false
            ),

            'mainTree'=>array(
                'class'=>'ext.actions.MainTreeAction',
                'modelName'=>'Content',
                //'showRoot'=>false
            ),

            'treePath'=>array(
                'class'=>'ext.actions.XAjaxEchoAction',
                'modelName'=>'Content',
                'attributeName'=>'pathText',
            ),
        );
    }


    /*
     * список доков-дерево документов
     */
    public function actionIndex(){

        $this->render('tree');

    }

    /*
     * построение дерева
     */
    public function actionTree(){
        $this->renderPartial('_tree');
    }

} 