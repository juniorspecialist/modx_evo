<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 07.08.14
 * Time: 23:25
 */

class Wayfinder extends  Ditto{

    public $hideSubMenus;// скрывать или нет суб-меню
    public $outerClass;//класс для CSS меню
    public $startId;//ID страницы с которой будем выбирать дочерние страницы
    public $sortBy;//по какому параметру сортировать
    public $sortDir;//направление сортировки


    /*
     * валидация параметров
     */
    public function validateParams(){

    }
    /*
     * парсим строку вызова сниппета, разбираем её и определеяем её параметры для вызова
     */
    public function parseString(){

        //строка вызова пример - Ditto? &tpl=`radiator-tovar-ditto` &extenders=`request`
        $this->callString = str_replace('Wayfinder?', '', $this->callString);

        //echo $this->callString.'<br>';

        //получаем массив параметров для вызова обработки
        $params_list = explode('&', $this->callString);

        //echo '<pre>'; print_r($params_list);

        foreach($params_list as $index=>$param){
            if(preg_match('/=/',$param)){
                //echo 'i='.$index.'<br>';//echo 'p='.$param.'<br>';
                $expl_list = explode('=',$param);
                $this->{$expl_list[0]} = trim(str_replace(array('`'),'',$expl_list[1]));
            }
        }
        $this->action();
    }

    /*
     * список перменных получили, обработка входящих параметров и вывод реультата
     */
    public function action(){

        //список документов, которые будут условием выборки данных
        $list = array();

        //применим список ID доков по которым будем делать поиск контента
        $criteria = new EMongoCriteria();
        $criteria->condition = array('menutitle' => array('$gt' => ''));
        $criteria->addCondition('parent',$this->startId);
        //$criteria->addCondition('hidemenu',0);//'hidemenu' => '0'
        //'condition' => array('deleted' => array('$ne' => 1)),


        //echo 'startId='.$this->startId.'<br>';


        if(!empty($this->sortBy)){
            $criteria->sort = array($this->sortBy=> 'asc');
            if(empty($this->sortDir)){
                //$criteria->setSort(array($this->sortBy, '1'));

            }else{
                //$criteria->setSort(array($this->sortBy, '-1'));

            }
        }else{
            $criteria->sort = array('menuindex'=> 'asc');
        }

        //$criteria->sort = array('menuindex'=> 'asc','menutitle'=>'asc');
        //$criteria->sort = array('menutitle'=>'asc');

        $find = Content::model()->find($criteria);

        $this->result = '<ul class="'.$this->outerClass.'">';

        foreach($find as $model) {
            //<a href="/portfolio.html" title="Наши клиенты">Клиенты</a>.
            //http://modx/[(site_url)]?r=site/index&alias=produkcija_bofill
            $class = '';// class="active"

            //выделим текущую страницу в списке меню
            if(YiiBase::app()->request->url==Yii::app()->controller->createUrl('/site/index', array('alias'=>$model->alias))){
                $class = 'class="active" ';
            }elseif($model->id==$this->model->parent){//выделим родительскую страницу в меню
                $class = 'class="active" ';
            }

            $this->result.='<li '.$class.' >'.CHtml::link($model->menutitle,Yii::app()->controller->createUrl('/site/index', array('alias'=>$model->alias)), array('title'=>$model->pagetitle)).'</li>';
        }
        $this->result.= '</ul>';

        //echo 'result='.$this->result.'<br>';
    }
}