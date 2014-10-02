<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.08.14
 * Time: 22:04
 */

class Content extends EMongoDocument {

    //public $id;
    public $contentType;
    public $pagetitle;
    public $description;
    public $alias;
    public $published;
    public $pub_date;
    public $content;
    public $isfolder;
    public $template;
    public $menuindex;
    public $searchable;
    public $cacheable;
    public $createdby;
    public $createdon;
    public $editedby;
    public $deleted;
    public $publishedon;
    public $menutitle;
    public $hidemenu;
    public $parent;
    public $introtext;

    //список тв-параметров по документу по которым заполнены значения(
    //полный список тв-параметров хранится в подвязке к шаблону, котор. использует документ, там все тв-параметры
    //включая те, у которых нет значений
    public $tv = array();

    const  prefix_cache = 'content_';


    /**
     * Gets a list of the projected fields for the model
     * @return array|string[]
     */
    public function getTv()
    {
        return $this->tv;
    }

    /**
     * Sets the projected fields of the model
     * @param array|string[] $fields
     */
    public function setTv(array $fields)
    {
        $this->tv = $fields;
    }


    public function searchD()
    {

        $criteria = new EMongoCriteria();

        if($this->_id){$criteria->compare('_id',$this->_id);}

        if($this->alias){$criteria->compare('alias', $this->alias, true); }

        if($this->pagetitle){$criteria->compare('pagetitle',$this->pagetitle, true);}

        return new EMongoDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>50,
            ),
        ));


    }


    function collectionName(){
        return 'Content';
    }

    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    function rules(){
        return array(
            array('pagetitle,template,parent, alias','required'),
            array('alias', 'EMongoUniqueValidator', 'className' => 'Content', 'attributeName' => 'alias'),
            /*array('contentType,pagetitle,description,alias,menutitle','length','max'=>255),

            array('published,pub_date,unpub_date,parent,isfolder,template,menuindex,searchable,cacheable,createdby','max'=>10),

            array('createdon,editedby,editedon,deleted,hidemenu','max'=>10),

            array('content','length','max'=>5500),
            */
            array('_id,pagetitle,alias', 'safe', 'on' => 'search') // search by title
        );
    }


    public function attributeLabels()
    {
        return array(
            'pagetitle'=>'Заголовок страницы',
            'contentType'=>'Тип документа',
            'description'=>'Описание',
            'alias'=>'Псевдоним',
            'published'=>'Публиковать',
            'pub_date'=>'Дата публикации',
            'content'=>'Содержимое документа',
            'isfolder'=>'',
            'template'=>'Шаблон',
            'menuindex'=>'Позиция в меню',
            'searchable'=>'Использовать в поиске',
            'createdby'=>'Создан',
            'menutitle'=>'Пункт меню',
            'parent'=>'Родительский ресурс',
            'hidemenu'=>'Не показывать в меню',
            'introtext'=>'Аннотация (введение)',
            'cacheable'=>'Кэшируемый',
        );
    }

    public function getPrimaryKey($value=null){
        if($value===null){
            $value=$this->_id;
        }
            //$value=$this->{$this->primaryKey()};

        return (int)$this->_id;
    }

    public function behaviors(){

        return array(
            'EMongoTimestampBehaviour' => array(
                'class' => 'EMongoTimestampBehaviour' // adds a nice create_time and update_time Mongodate to our docs
            ),

            'TreeBehavior' => array(
              'class' => 'ext.behaviors.XTreeBehavior',
              'treeUrlMethod'=>'linkUpdateDoc',
              'treeLabelMethod'=>'labelTreeElement',
            ),
            //заполнение параметров документа по умолчанию
            'DefaultBehavior'=>array(
                'class'=>'ext.behaviors.DefaultBehavior'
            ),
        );
    }

    public function relations(){
        return array(
            //'author' => array('one','User','_id','on'=>'userId'),
            'tpl' => array('one','Template','_id', 'on'=>'template'),
            'parent_doc'=>array('one','Content','_id', 'on'=>'parent'),
            'children' => array('many', 'Content', 'parent', 'on'=>'_id'),
            //'childCount' => array('many', 'Content', 'parent', 'on'=>'childCount'),
        );
    }

    /*
     * метод для формирования ссылок на редактирования документов
     */
    public function linkUpdateDoc(){
        return YiiBase::app()->createAbsoluteUrl('/manager/page/update', array('id'=>$this->_id));
    }

    /*
     * формируем Label для элементов дерева-документов
     */
    public function labelTreeElement(){
        return $this->pagetitle.'  ('.$this->_id.')';
    }

    /*
     * подсчитываем кол-во дочерних элементов
     */
    public function getChildCount(){

        $count = 0;

        //$count = count($this->children);

        $childrens = $this->children;

        if(!empty($childrens)){
            foreach($childrens as $children){
                $count++;
            }
        }else{
            $count = 0;
        }

        return $count;
    }
    /*
     * после того как нашли модель - документ, преобразуем некие значения в нужный формат
     */
    public function afterFind(){
        parent::afterFind();
        if(empty($this->pub_date) || $this->pub_date==0){
            $this->pub_date = '';
        }
    }
} 