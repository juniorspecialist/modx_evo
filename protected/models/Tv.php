<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.09.14
 * Time: 23:20
 */

/*
 * модель для работы с тв-параметрами системы
 */
class Tv  extends EMongoDocument {

    //public $id;
    public $type;//выпадающий список с выбором значений-текст,число,картинка
    public $name;//[*name*]
    public $caption;
    public $description;
    public $elements;//1||0    ||-разделитель значений
    public $default_text;



    const  prefix_cache = 'tv_';

    function collectionName(){
        return 'Tv';
    }

    /*
     * находим список шаблонов, к которым подвязан данный тв-параметр
     */
    public function getTemplatesByTvParam(){

        $criteria = new EMongoCriteria();

        $criteria->compare('tv.'.$this->name,$this->name);

        $criteria->sort = array('title'=> 'asc');

        $find = Template::model()->find($criteria);

        $list = array();

        if(!empty($find)){
            foreach($find as $row){
                $list[] = $row->_id;
            }
        }

        return $list;
    }

    function rules(){
        return array(
            array('type,name,caption','required'),
            array('name', 'EMongoUniqueValidator', 'className' => 'Tv', 'attributeName' => 'name'),
            array('caption,description','length','max'=>255),
            array('elements,default_text','length','max'=>10000),
            array('name,caption', 'safe', 'on' => 'search') // search by title
        );
    }

    public function searchD()
    {

        $criteria = new EMongoCriteria();

        if($this->caption){$criteria->compare('caption',$this->caption, true);}

        if($this->name){$criteria->compare('name', $this->name, true); }

        return new EMongoDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>50,
            ),
        ));

    }

    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function attributeLabels()
    {
        return array(
            'type'=>'Тип ввода',
            'name'=>'Имя параметра',
            'caption'=>'Заголовок',
            'default_text'=>'Значение по умолчанию',
            'elements'=>'Возможные значения',
            //'id'=>'ID',
            'description'=>'Описание',
        );
    }


    /*
     * список возможных типов тв-параметра
     */
    static function getTypeList(){
        return array(
            'text'=>'Text',
            'dropdown'=>'DropDown List Menu',
            'listbox'=>'Listbox (Single-Select)',
            'option'=>'Radio Options',
            'checkbox'=>'Check Box',
            'image'=>'Image',
            'number'=>'Number',
        );
    }

    /*
     * ищем тв-параметр по его названию(имени)
     */
    public function getTvParamByName($tv_name){

        if(empty($tv_name)){ return 'empty name tv-param';}

        $criteria = new EMongoCriteria(array(
            'condition' => array('name'=>trim($tv_name)),
        ));
        $model = Tv::model()->findOne($criteria);

        return $model;
    }

//    public function getPrimaryKey($value=null){
//        if($value===null){
//            $value=$this->id;
//        }
//        //$value=$this->{$this->primaryKey()};
//
//        return (int)$this->id;;
//    }
} 