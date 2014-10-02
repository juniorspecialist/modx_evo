<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 03.08.14
 * Time: 21:56
 */

/*
 * шаблоны, которые использует системы для отображения нужной разметки по странице
 * т.е. у каждой страницы свой шаблон,
 * к шаблону подвязан список тв-параметров, которые могут быть заполнены в документе
 * есть связь между документом и шаблонов
 * грубо говоря шаблон это контейнер который хранит в себе разметку+ вызовы чанков и сниппетов
 */
class Template extends EMongoDocument {

    //public $id;//ID из DB mysql
    public $title;//название шаблона
    public $desc;// описание
    public $content;//содержимое шаблона - html код+вызовы всяких чанков и т.д.

    //тв-параметры, которые использует шаблон
    //а если использует шаблон, значит и доступны для заполнения и в документе который использует шаблон
    public $tv = array();

    function collectionName(){
        return 'Template';
    }

    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function getPrimaryKey($value=null){
        if($value===null){
            $value=(string)$this->_id;
        }
            //$value=$this->{$this->primaryKey()};

        return (string)$value;
    }

    public function searchD()
    {

        $criteria = new EMongoCriteria();

        if($this->title){$criteria->compare('title',$this->title, true);}

        if($this->desc){$criteria->compare('desc', $this->desc, true); }

        return new EMongoDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>50,
            ),
        ));

    }

    function rules(){
        return array(
            array('title','required'),
            array('title', 'EMongoUniqueValidator', 'className' => 'Template', 'attributeName' => 'title'),
            //array('title','length','max'=>500),
            //array('content','length','max'=>10000),
            array('title,desc, content', 'safe', 'on' => 'search') // search by title
        );
    }

    public function attributeLabels()
    {
        return array(
            'title'=>'Название',
            'desc'=>'Краткое описание',
            'content'=>'Содержимое шаблона',
        );
    }

    /*
     * получаем список(массив) шаблонов системы
     */
    static function getTemplateList(){
        //список шаблонов
        $list = array();

        //применим список ID доков по которым будем делать поиск контента
        $criteria = new EMongoCriteria();
        //$criteria->condition = array('menutitle' => array('$gt' => ''));
        //$criteria->addCondition('parent',$this->startId);

        $criteria->sort = array('title'=> 'asc');

        $find = Template::model()->find($criteria);

        foreach($find as $template) {
            $list[$template->_id] = $template->title;
        }

        return $list;
    }

    /*
     * проставляем-убираем привязки к списку шаблонов по тв-параметру
     * $name_tv - имя тв-параметра, которое будем привязывать к списку шаблонов
     * $templates - список шаблонов для связи с  тв-параметром
     * $action - действие по подвязыванию или отвязыванию тв-параметра к списку шаблонов
     * $action - relation(подвязвание), disconnection - отвязывание
     */
    public function relationTvParamWithTemplates($name_tv, $templates, $action = 'relation'){

        //подвязываем тв-параметр к списку шаблонов
        if($action=='relation'){
            //находим в цикле каждый шаблон отдельно
            foreach($templates as $tpl){

                $criteria = new EMongoCriteria();

                $criteria->addCondition('_id',$tpl);

                $model = Template::model()->findOne($criteria);

                if(!empty($model)){
                    $model->tv[$name_tv] = $name_tv;
                    $model->save();
                }
            }
        }else{
            //отвязываем тв-параметр от списка шаблонов
            //сперва находим список шаблонов, в которых используется данный тв-параметр
            $criteria = new EMongoCriteria(array('condition' => array('tv.'.$name_tv=>$name_tv)));

            $find = Template::model()->find($criteria);

            foreach($find as $model) {
                $tv = $model->tv;
                unset($tv[$name_tv]);
                $model->tv = $tv;
                $model->save();
            }
        }
    }
}