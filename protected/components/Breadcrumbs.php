<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11.08.14
 * Time: 19:22
 */

/*
 * сниппет хлебных крошек
 * т.е. выводим список ссылок на верхние уровни до самого верха
 */
class Breadcrumbs {

    public $model;//текущий документ

    public function __construct($model){
        $this->model = $model;
    }

    /*
     * ищем заголовок родителя
     */
    public function getParent($parent_id){
        //применим список ID доков по которым будем делать поиск контента
        $criteria = new EMongoCriteria();
        $criteria->addCondition('_id',$parent_id);
        $criteria->setLimit(1);

        $parent = Content::model()->findOne($criteria);

        return $parent;
    }

    public function run(){

        $list = array();
        $link = array();

        $model = $this->model;

        $list[]= $model->menutitle;

        //максимально может быть не более 20ти уровней
        for($i=0;$i<10;$i++){

            if(empty($parent)){continue;}
            //var_dump($parent->parent);
            if(empty($parent->menutitle)){
                //break;
            }else{
                if(empty($parent->menutitle)){
                    continue;
                }

                $list[]=$this->getMenuLink($parent->menutitle, $parent->alias);

                $model = $parent;
            }
        }

        $list = array_reverse($list);

        if(count($list)==1){
            return '';
        }else{
            return implode('»',$list);
        }
    }

    /*
     * формируем ссылку в меню хлебных крошек
     * $title - название ссылки
     * $alias - адрес который будет в ссылке
     */
    public function getMenuLink($title, $alias){
        return CHtml::link($title, Yii::app()->controller->createUrl('/site/index', array('alias'=>$alias)));
    }

    /*
     * получаем список хлебных крошек до самого вверхнего уровня
     */
    public function crumbsList(){

    }
} 