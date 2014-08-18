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

        $model = $this->model;

        //максимально может быть не более 20ти уровней
        for($i=0;$i<10;$i++){
            $parent = $this->getParent($model->parent);
            //var_dump($parent->parent);
            if(empty($parent->menutitle)){
                break;
            }else{
                $list[]=$parent->menutitle;
                $model = $parent;
            }
            //$model = $parent;
            //echo $model->menutitle.'<br>';
        }
        //die();

        $list = array_reverse($list);

        return implode('»',$list);
    }

    /*
     * получаем список хлебных крошек до самого вверхнего уровня
     */
    public function crumbsList(){

    }
} 