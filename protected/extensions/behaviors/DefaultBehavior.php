<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.09.14
 * Time: 16:20
 */

/*
 * на основании настроек заполняем значениями по-умолчанию
 * т.е. применяем настройки при разных ситуауиях, т.ч. при создании форм на лету
 */
class DefaultBehavior extends  CActiveRecordBehavior{
    /*
     *
     */
    public  function afterConstruct($event)
    {
        //применяем настройки по умоланию для создания документов
        if($this->getOwner()->collectionName()=='Content'){
            //какой по умолчанию шаблон использовать, на его основании будет создан список полей
            $this->getOwner()->template = Yii::app()->config->get('SYSTEM.DEFAULT_TEMPLATE');
        }

        parent::afterConstruct($event);
    }
    //
} 