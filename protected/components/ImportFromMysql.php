<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 02.08.14
 * Time: 14:40
 */

/*
 * переж началом импорта незабыть
 * php.ini добавить mongo.allow_empty_keys = 1 - чтобы можно было писать пустые значения в доках монго+ подключить монго и настроить - extension=mongo.so
 */

/*
  * импорт данных из mysql-базы в монго
  */
class ImportFromMysql{


    public function import(){
        //импортируем статьи
        $this->importContent();

        //импортируем список шаблонов системы+ подвязки по ним списка тв-параметров(1шаблон->много тв-параметров)
        //$this->importTemplate();

        // импортируем список чанков
        //$this->importChunk();

        //импортируем список тв-параметров с их значениями
        //$this->importTvParams();
    }

    /*
     * импорт тв-параметров(списоких настройки и параметры)
     */
    public function importTvParams(){
        $sql = 'SELECT * FROM modx_site_tmplvars';
        $rows = YiiBase::app()->db->createCommand($sql)->queryAll();
        foreach($rows as $row){
            $tv = new Tv();
            //$tv->id = (int)$row['id'];
            $tv->type = $row['type'];
            $tv->name = $row['name'];
            $tv->caption = $row['caption'];
            $tv->description = $row['description'];
            $tv->elements = $row['elements'];
            $tv->default_text = $row['default_text'];
            $tv->save();
        }
    }

    /*
     * импорт чанков в систему
     */
    public function importChunk(){
        $sql = 'SELECT * FROM modx_site_htmlsnippets';
        $rows = YiiBase::app()->db->createCommand($sql)->queryAll();
        foreach($rows as $row){
            $chunk = new Chunk();
            $chunk->title = $row['name'];
            $chunk->desc = $row['description'];
            $chunk->content = $row['snippet'];
            $chunk->save();
        }
    }

    /*
     * импорт списка документов-статей из БД Modx Evo
     * +пишим тв-параметры по каждому документу
     */
    public function importContent(){

        $sql='SELECT * FROM modx_site_content';

        $dataProvider=new CArrayDataProvider(YiiBase::app()->db->createCommand($sql)->queryAll());

        $iterator=new CDataProviderIterator($dataProvider,5000);

        // обходим данные для каждой строки выборки
        foreach($iterator as $row){

            $content = new Content();
            $content->_id = (int)$row['id'];
            //$content->id = ()$row['id'];
            $content->contentType = $row['contentType'];
            $content->pagetitle = $row['pagetitle'];
            $content->description = $row['description'];
            $content->alias = $row['alias'];
            $content->published = $row['published'];
            $content->pub_date = $row['pub_date'];
            $content->content = $row['content'];
            $content->isfolder = $row['isfolder'];
            $content->template = (string)$row['template'];
            $content->menuindex = $row['menuindex'];
            $content->searchable = $row['searchable'];
            $content->cacheable = $row['cacheable'];
            $content->createdby = $row['createdby'];
            $content->createdon = $row['createdon'];
            $content->editedby = $row['editedby'];
            $content->deleted = $row['deleted'];
            $content->publishedon = $row['publishedon'];
            $content->menutitle = $row['menutitle'];
            $content->hidemenu = $row['hidemenu'];
            //если PARENT=0, привяжим его к первому уровню, для построения корректного дерева
            if($row['id']==1){
                //укажим начало ДЕРЕВА(главное звено)
                $content->parent = 0;
            }else{
                //если не указана подвязка, то подвязываем к самому первому элементу дерева
                if($row['parent']==0){
                    $content->parent = 1;
                }else{
                    $content->parent = (int)$row['parent'];
                }
            }



            //получаем список тв-параметров по документу+названия этих тв-параметров
            $tv_params = array();

            $tvs = YiiBase::app()->db->createCommand('SELECT * FROM modx_site_tmplvar_contentvalues WHERE contentid="'.$row['id'].'"')->queryAll();
            if(!empty($tvs)){
                foreach($tvs as $tv){
                    //получаем по каждому тв-параметру его название
                    $tv_name_row = YiiBase::app()->db->createCommand('SELECT name FROM modx_site_tmplvars WHERE id="'.$tv['tmplvarid'].'"')->queryRow();
                    if(!empty($tv_name_row['name'])){
                        $tv_params[$tv_name_row['name']] = $tv['value'];
                    }
                }
            }
            $content->tv = $tv_params;

            //echo '<pre>'; print_r($content);

            //не валидируем данные, если записываем корень всей структуры - дерева
            if($content->parent==0){
                $content->save(false);
            }else{
                if($content->validate()){
                    $content->save();
                    //echo 'save<br>';die();
                }else{
                    //echo '<pre>'; print_r($content->getErrors());//die();
                }
            }
        }
    }

    /*
     * импортируем список шаблонов
     * +список подвязанных к шаблону тв-параметров
     */
    public function importTemplate(){
        $db = YiiBase::app()->db;
        //получаем список шаблонов
        $tpl_list = $db->createCommand('SELECT * FROM modx_site_templates')->queryAll();
        if(!empty($tpl_list)){
            foreach($tpl_list as $tpl){
                //по каждому шаблону находим список подвязанных к нему тв-параметров и запишим их
                $tv_params_by_tpl = $db->createCommand('SELECT tmplvarid FROM modx_site_tmplvar_templates WHERE templateid="'.$tpl['id'].'"')->queryAll();

                $template = new Template();
                $template->_id = $tpl['id'];
                $template->title = $tpl['templatename'];
                $template->desc = $tpl['description'];
                $template->content = $tpl['content'];

                $tv_params = array();

                foreach($tv_params_by_tpl as $tv_id){
                    //получаем по каждому тв-параметру его название
                    $tv_name_row = YiiBase::app()->db->createCommand('SELECT name FROM modx_site_tmplvars WHERE id="'.$tv_id['tmplvarid'].'"')->queryRow();
                    $tv_params[$tv_name_row['name']] = $tv_name_row['name'];
                }


                //подвяжем список тв-параметров к шаблону
                $template->tv = $tv_params;

                $template->save();
            }
        }



    }
}