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

    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    static function findChunkByName($nameChunk=''){

        if(empty($nameChunk)){ return 'empty name chunk';}

        $nameChunk = str_replace('"', '', $nameChunk);
        $nameChunk = str_replace('{', '', $nameChunk);
        $nameChunk = str_replace('}', '', $nameChunk);

//        //проверим наличие чанка в кеше, если его нет, тогда запрос к бд и сохранение в кеше
//        $chunke_cache = Yii::app()->cache->get(Chunk::prefix_cache.$nameChunk);
//        if(empty($chunke_cache)){
            $criteria = new EMongoCriteria(array(
                'condition' => array('title'=>trim($nameChunk)),
            ));
            $model = Chunk::model()->findOne($criteria);

            //Yii::app()->cache->set(Chunk::prefix_cache.$nameChunk,$model->content);

            if(empty($model->content)){
                return '';
            }else{
                return $model->content;
            }
//        }else{
//            return $chunke_cache;
//        }
    }
} 