<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.09.14
 * Time: 11:58
 */

class Config extends EMongoDocument
{

    public $param;//параметр в настройках
    public $value;//значение параметра
    public $label;//название параметра(заголовок)
    public $type;//тип параметра
    public $default;//значение  параметра по умолчанию

    const  prefix_cache = 'config_';


    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    function collectionName(){
        return 'Config';
    }


    function rules(){
        return array(
            array('param,value','required'),
            array('value', 'safe'),
            array('param', 'EMongoUniqueValidator', 'className' => 'Config', 'attributeName' => 'param'),
            array('param, value, label, type,','length','max'=>512),
            array('_id, param, value, label, type, default', 'safe', 'on'=>'search'),
        );
    }
    public function attributeLabels()
    {
        return array(
            'param'=>'Имя параметра',
            'value'=>'Значение параметра',
            'label'=>'Название параметра',
            'default'=>'Значение по умолчанию',
        );
    }
}