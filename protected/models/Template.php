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

    public $id;//ID из DB mysql
    public $title;//название шаблона
    public $desc;// описание
    public $content;//содержимое шаблона - html код+вызовы всяких чанков и т.д.

    //тв-параметры, которые использует шаблон
    //а если использует шаблон, значит и доступны для заполнения и в документе который использует шаблон
    public $tv = array();

    function collectionName(){
        return 'template';
    }

    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function getPrimaryKey($value=null){
        if($value===null)
            //$value=$this->{$this->primaryKey()};
            $value=$this->id;
        return (string)$value;
    }
} 