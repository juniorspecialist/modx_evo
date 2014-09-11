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
        $criteria->addCondition('id',$parent_id);
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
            $parent = $this->getParent($model->parent);
            //var_dump($parent->parent);
            if(empty($parent->menutitle)){
                break;
            }else{
                $list[]=$this->getMenuLink($parent->menutitle, $parent->alias);
                //array_push($list, $this->getMenuLink($parent->menutitle, $parent->alias));
                $model = $parent;
            }
            //$model = $parent;
            //echo $model->menutitle.'<br>';
        }
        //die();

        $list = array_reverse($list);

//        $reslt = array();
//
//        foreach($list as $url){
//            $reslt[] = Yii::app()->controller->createUrl('/site/index', array('alias'=>$url));
//        }

        return implode('»',$list);
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