<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.08.14
 * Time: 22:04
 */

class Content extends EMongoDocument {

    public $id;
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

    //список тв-параметров по документу по которым заполнены значения(
    //полный список тв-параметров хранится в подвязке к шаблону, котор. использует документ, там все тв-параметры
    //включая те, у которых нет значений
    public $tv = array();


    function collectionName(){
        return 'Content';
    }

    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    function rules(){
        return array(
            //array('id','max'=>10),
            //array('contentType,pagetitle,content,isfolder,template','required'),
            /*array('contentType,pagetitle,description,alias,menutitle','length','max'=>255),

            array('published,pub_date,unpub_date,parent,isfolder,template,menuindex,searchable,cacheable,createdby','max'=>10),

            array('createdon,editedby,editedon,deleted,hidemenu','max'=>10),

            array('content','length','max'=>5500),
            */
            //array(''),

            //array('title', 'safe', 'on' => 'search') // search by title
        );
    }

    public function getPrimaryKey($value=null){
        if($value===null){
            $value=$this->id;
        }
            //$value=$this->{$this->primaryKey()};

        return (string)$this->id;;
    }

    public function behaviors(){

        return array(
            'EMongoTimestampBehaviour' => array(
                'class' => 'EMongoTimestampBehaviour' // adds a nice create_time and update_time Mongodate to our docs
            ),

            'TreeBehavior' => array(
              'class' => 'ext.behaviors.XTreeBehavior',
            ),
        );
    }

    public function relations(){
        return array(
            //'author' => array('one','User','_id','on'=>'userId'),
            'tpl' => array('one','Template','id', 'on'=>'template'),
            '//parent_doc'=>array('one','Content','id', 'on'=>'parent'),

            // Here we define the likes/dislikes relationships
            //'usersLiked' => array('many', 'User', '_id','on'=>'likes'),
            //'usersDisliked' => array('many', 'User','_id','on'=>'dislikes')
            //'comments' => array('many','Comment','on' => '_id','articleId'),
            //'parent' => array('one', 'Content', 'parent', 'on'=>'template'),
            'children' => array('many', 'Content', 'parent', 'on'=>'id'),
            //'childCount' => array('many', 'Content', 'parent', 'on'=>'childCount'),
        );
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
} 