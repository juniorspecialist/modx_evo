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

    public $filter;//фильтр который используется для заданий условий выборки для дитто

    public $display;//сколько найденных позиций показывать(пока что используем этот как лимит при отборе)

    public function __construct($model,$callString){
        $this->model = $model;
        $this->callString = $callString;
    }

    /*
     * парсим фильтрацию для дитто и преобразовываем в условие EMongoCriteria
     * &filter - Фильтр для отсеивания документов
        Формат: поле,критерий,тип сравнения
        Значение по умолчанию: NULL
        Примечание: Используется формат `поле,критерий,тип сравнения` с запятой между значениями.
    если поле начинается с букв "tv" - значит это тв-параметр для фильтрации
        Список фильтров:
        По умолчанию: NULL
        Типы сравнения:
        1 - != (не соответствует критерию)
        2 - == (соответствует критерию)
        3 - < (меньше критерия)
        4 - > (больше критерия)
        5 - <= (меньше или равен критерию)
        6 - >= (больше или равен критерию)
        TODO доделать:7 - (не содержит текст критерия),8 - (содержит текст критерия)

        Может содержать несколько запросов, разделенных глобальным разделителем |.
     */
    public function getFilterDitto(){

        $criteria = new EMongoCriteria();

        //определим скольско условий было прописано в параметре
        $condition_list = explode('|',$this->filter);

        //условия из массива который преобразовываем в EMongoCriteria
        $criteria_condition_list = array();

        //пробегаемся по списку условий и добавляем их в EMongoCriteria
        foreach($condition_list as $condition){

            $filter_tv = false;//если TRUE - ильтруем по тв-параметру

            //парсим одно из условий и определяем что за параметры использовать при фильтрации и с какими условиями
            //параметры условия разделены запятой(например - tvnasos,канализационные и дренажные,1)
            $params_list = explode(',', $condition);

            //определим используется ли фильтр по тв-параметрам или по параметрам документа
            if(preg_match('/^tv(.*?)/si',$params_list[0], $matches)){
                $params_list[0] = $matches[0];//перезаписали без приставки
                $filter_tv = true;
            }

            //$params_list[0] - поле для условия,$params_list[1]-значение для условия,$params_list[2] - тип сравнения
            $compare = $this->getCompareFilterCondition((int)$params_list[2]);

            //если пустое значение, значит просто РОВНО, если нет, используем оператор условия
            if(empty($compare)){
                //$c->compare('i', '<4');
                $criteria_condition_list[] = array('');
            }else{
                $criteria_condition_list[] = array('');
            }

        }

        return $criteria;
    }

    /*
     * список соответствий цифре в условии фильтрации по Дитто
     * тому типу сравнения к которому оно подвязано
     * $number_of_compare - цифра, которая показывает какое условие будем применять к фильтру по значению
     */
    public function getCompareFilterCondition($number_of_compare=''){
        if(empty($number_of_compare)){
            return array(
                1=>'$ne',//!= не соответствует критерию)
                2=>'',
                3=>'$lt',//<(меньше критерия)
                4=>'$gt',//>(больше критерия)
                5=>'$lte',//<= (меньше или равен критерию)
                6=>'$gte',//>= больше или равен критерию)
            );
        }else{
            $list = self::getCompareFilterCondition();
            return $list[$number_of_compare];
        }
    }


    /*
     * парсим строку вызова сниппета, разбираем её и определеяем её параметры для вызова
     */
    public function parseString(){

        //строка вызова пример - Ditto? &tpl=`radiator-tovar-ditto` &extenders=`request`
        $this->callString = str_replace('Ditto?', '', $this->callString);

        //получаем массив параметров для вызова обработки
        $params_list = explode('&', $this->callString);

        foreach($params_list as $index=>$param){
            if(preg_match('/=`(.*?)`/',$param, $matches)){

                $param = str_replace(array('&','amp;','!]',']]','[!','[['), '', $param);

                $expl_list = explode('=',$param);

                $this->{$expl_list[0]} = trim(str_replace(array('`'),'',$matches[1]));
            }
        }

        //получаем содержимое шаблона вывода
        $this->getContentTpl();

        $this->action();
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

            //если указан был лимит - учитываем его
            if(!empty($this->display)){$criteria->limit = $this->display ;}

            $find = Content::model()->find($criteria);

            foreach($find as $model) {
                $this->result.=Parser::mergeTvParamsContent($this->content_tpl, $model);
            }
        }elseif(!empty($this->parent)||empty($this->parent) && empty($this->documents)){//выборка всех потомков по текущему документу, т.е. тек. документ - родитель
            //не указано, какие доки выбирать выбираем дочерние элементы тек. дока

            $criteria = new EMongoCriteria(array('condition' => array('parent'=>$this->model->id)));

            //если указан был лимит - учитываем его
            if(!empty($this->display)){$criteria->limit = $this->display ;}

            $models = Content::model()->find($criteria);

            foreach($models as $model){
                $this->result.=Parser::mergeTvParamsContent($this->content_tpl, $model);
            }
        }
    }

    /*
     * находим шаблон для вывода данных
     */
    public function getContentTpl(){
        if(!empty($this->tpl)){
            $this->content_tpl = Chunk::findChunkByName($this->tpl);
        }else{
            die('empty tpl in call string Ditto:'.$this->callString );
        }
    }

} 