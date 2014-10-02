<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24.09.14
 * Time: 20:35
 */

/*
 * класс для работы с настройками системы вообщем
 * т.е. храним общие настройки системы, которые используем в работе
 * настройки храним в коллекции MongoDB
 */
//http://www.elisdn.ru/blog/21/yii-i-hranenie-nastroek-v-baze-dannih
//TODO доделать возможность применение параметров из настроек к работе системы
class Settings  extends CApplicationComponent{

    public $cache = 0;
    public $dependency = null;

    public $prefix_cache = 'settings_';

    protected $data = array();

    public function init()
    {
        $data = $this->getDbConnection();

        //$items = $db->createCommand('SELECT * FROM {{config}}')->queryAll();
        //$mongo->selectCollection('new_collection')
        //$items = $db->find();

        foreach ($data as $item)
        {
            if ($item->param){
                $this->data[$item->param] = $item->value === '' ?  $item->default : $item->value;
            }

        }

        parent::init();
    }

    public function get($key)
    {
        if (array_key_exists($key, $this->data))
            return $this->data[$key];
        else
            throw new CException('Undefined parameter ' . $key);
    }

    public function set($key, $value)
    {
        $model = Config::model()->findOne(array('param'=>$key));
        if (!$model)
            throw new CException('Undefined parameter ' . $key);

        $model->value = $value;

        if ($model->save()){
            $this->data[$key] = $value;
        }
    }

    public function add($params)
    {
        if (isset($params[0]) && is_array($params[0]))
        {
            foreach ($params as $item){
                $this->createParameter($item);
            }

        }
        elseif ($params){
            $this->createParameter($params);
        }
        //обновление данных+ обновление кеша
        YiiBase::app()->cache->delete($this->prefix_cache);

        $this->init();
    }

    public function delete($key)
    {
        if (is_array($key))
        {
            foreach ($key as $item){
                $this->removeParameter($item);
            }
        }
        elseif ($key){
            $this->removeParameter($key);
        }


        //обновление данных+ обновление кеша
        YiiBase::app()->cache->delete($this->prefix_cache);

        $this->init();
    }

    /*
     * возвращаем массив настроек из коллекции МонгоДБ
     */
    protected function getDbConnection()
    {
        if ($this->cache){
            //проверим есть ли данные в кеше, если нет данных в кеше делаем выборку и записываем в кеш выборку
            $value=Yii::app()->cache->get($this->prefix_cache);

            if($value===false)
            {
                // устанавливаем значение $value заново, т.к. оно не найдено в кэше,
                // и сохраняем его в кэше для дальнейшего использования:
                $data = Config::model()->find();
                if(!empty($data)){
                    $list = array();
                    foreach($data as $row){
                        $list[] = $row;
                    }
                    // закешировали значения на указанное время в настройках
                    Yii::app()->cache->set($this->prefix_cache,$list, $this->cache);
                }
                $value = $list;
            }

        }else{
            $data = Config::model()->find();
            $list = array();
            if(!empty($data)){

                foreach($data as $row){
                    $list[] = $row;
                }
                // закешировали значения на указанное время в настройках
                //Yii::app()->cache->set($this->prefix_cache,$list, $this->cache);
            }
            $value = $list;
        }


        return $value;
    }

    /*
     * при добавлении нового параметра:1)добавим его в массив данных, 2)добавим его в закешированные данные
     */
    protected function createParameter($param)
    {
        if (!empty($param['param']))
        {
            $model = Config::model()->findOne(array('param' => $param['param']));

            //если новый параметр, то создадим новую модель
            if ($model === null){
                $model = new Config();
            }

            $model->param = $param['param'];
            $model->label = isset($param['label']) ? $param['label'] : $param['param'];
            $model->value = isset($param['value']) ? $param['value'] : '';
            $model->default = isset($param['default']) ? $param['default'] : '';
            $model->type = isset($param['type']) ? $param['type'] : 'string';

            //если добавили новый элемент в конфиг, очистим кеш и закешируем зановов данные
            if($model->validate()){
                $model->save();

                //обновление данных+ обновление кеша
                YiiBase::app()->cache->delete($this->prefix_cache);
                $this->init();
            }
        }
    }

    protected function removeParameter($key)
    {
        if (!empty($key))
        {
            $model = Config::model()->findOne(array('param'=>$key));
            if ($model){
                $model->delete();
            }
        }
    }
} 