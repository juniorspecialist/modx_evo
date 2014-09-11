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
class TreeController extends Controller {

    public function actions()
    {
        return array(
            'fillTree'=>array(
                'class'=>'ext.actions.XFillTreeAction',
                'modelName'=>'Content',
                'showRoot'=>false
            ),

            'treePath'=>array(
                'class'=>'ext.actions.XAjaxEchoAction',
                'modelName'=>'Menu',
                'attributeName'=>'pathText',
            ),
        );
    }


    /*
     * список доков-дерево документов
     */
    public function actionIndex(){

    }

    /*
     * построение дерева
     */
    public function actionTree(){

    }

} 