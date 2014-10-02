<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 05.08.14
 * Time: 13:38
 */

class Chunk  extends EMongoDocument {

    public $title;//название чанка
    public $desc;//краткое описание чанка
    public $content;//содержимое чанка

    const  prefix_cache = 'chunk_';

    function collectionName(){
        return 'Chunk';
    }


    function rules(){
        return array(
            array('title,content','required'),
            array('title', 'EMongoUniqueValidator', 'className' => 'Chunk', 'attributeName' => 'title'),
            array('title,desc','length','max'=>255),
            array('content','length','max'=>10000),
            array('title,desc, content', 'safe', 'on' => 'search') // search by title
        );
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

    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    static function findChunkByName($nameChunk=''){

        if(empty($nameChunk)){ return 'empty name chunk';}

        $nameChunk = str_replace('"', '', $nameChunk);
        $nameChunk = str_replace('{', '', $nameChunk);
        $nameChunk = str_replace('}', '', $nameChunk);

        $criteria = new EMongoCriteria(array(
            'condition' => array('title'=>trim($nameChunk)),
        ));
        $model = Chunk::model()->findOne($criteria);


        if(empty($model->content)){
            return '';
        }else{
            return $model->content;
        }
    }

    public function attributeLabels()
    {
        return array(
            'title'=>'Название',
            'desc'=>'Краткое описание',
            'content'=>'Содержимое чанка',
        );
    }
} 