<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 06.08.14
 * Time: 18:29
 */

/*
 * обработка сниппета Ditto из модкс ево
 *
 */
class Ditto {
    public $model;//текущая страница

    public $callString;//строка вызова сниппета Ditto из кода страницы с параметрами

    public $result = '';// результат работы сниппета Ditto

    public $tpl;// шаблон для вывода данных(обычно какой-то чанк)

    public $parent;

    public $documents;//список документов через запятую, которые надо выбирать

    public $content_tpl;// содержиме чанка-шаблоны для вывода

    public function __construct($model,$callString){
        $this->model = $model;
        $this->callString = $callString;
    }

    /*
     * парсим строку вызова сниппета, разбираем её и определеяем её параметры для вызова
     */
    public function parseString(){

        //строка вызова пример - Ditto? &tpl=`radiator-tovar-ditto` &extenders=`request`
        $this->callString = str_replace('Ditto?', '', $this->callString);

        //получаем массив параметров для вызова обработки
        $params_list = explode('&', $this->callString);

        //print_r($params_list);

        foreach($params_list as $index=>$param){
            if(preg_match('/=/',$param)){
                //echo 'i='.$index.'<br>';
                //echo 'p='.$param.'<br>';
                $expl_list = explode('=',$param);

                $this->{$expl_list[0]} = trim(str_replace(array('`'),'',$expl_list[1]));
            }
        }

        //echo '<pre>'; print_r($this); die();

        //получаем содержимое шаблона вывода
        $this->getContentTpl();
        //die('content_tpl='.$this->content_tpl);
        $this->action();
        //echo '<pre>'; print_r($this);die();
    }

    /*
     * список перменных получили, обработка входящих параметров и вывод реультата
     */
    public function action(){

        //список документов, которые будут условием выборки данных
        $list = array();

        //если указан 1 или список документов - делаем выборку по ним
        if(!empty($this->documents)){

            $list = explode(',', str_replace(array('`',' '),'',$this->documents));

            //преобразуем список список ID в массив для условия выборки
            $condition = array();

            foreach($list as $id){$condition[] = array('id'=>$id);}

            //применим список ID доков по которым будем делать поиск контента
            $criteria = new EMongoCriteria();
            $criteria->addOrCondition($condition);
            $find = Content::model()->find($criteria);

            //var_dump($find); die();
            foreach($find as $model) {
                //var_dump($model->alias);
                $this->result.=Parser::mergeTvParamsContent($this->content_tpl, $model);
                //die($this->result);
            }
        }elseif(!empty($this->parent)||empty($this->parent) && empty($this->documents)){//выборка всех потомков по текущему документу, т.е. тек. документ - родитель
            //не указано, какие доки выбирать выбираем дочерние элементы тек. дока

            $criteria = new EMongoCriteria(array('condition' => array('parent'=>$this->model->id)));

            $models = Content::model()->find($criteria);

            foreach($models as $model){
                //echo $model->findOne()
                //$list[] = $model->id;
                $this->result.=Parser::mergeTvParamsContent($this->content_tpl, $model);
            }
        }
    }

    /*
     * находим шаблон для вывода данных
     */
    public function getContentTpl(){
        if(!empty($this->tpl)){
            //die(Chunk::findChunkByName($this->tpl));
            $this->content_tpl = Chunk::findChunkByName($this->tpl);
        }else{
            echo 'empty tpl';
        }
    }

} 