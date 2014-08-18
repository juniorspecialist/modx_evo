
<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 03.08.14
 * Time: 23:26
 */

/*
 * из смешанного набора тегов и разного рода обозначений на вызовы сниппетов - формируем html-код
 * т.е. первоначально у нас может быть html-код со вставками включений всяких виджетов+сниппетов+hml
 * в итоге получаем чистый HTML-код, заменяя все вызовы сниппетов и чанков и виджетов их значениями
 */
class Parser {

    public $template;//шаблон вывода для статьи-документа
    public $model;//массив с данными по документу
    public $html;//результирующий HTML-код, после всех обработок


    /*
     * $template - содержимое шаблона, который использует данный документ
     * $model - содержимое контента, коллекция Content
     */
    public function __construct($template='', $model){
        $this->model = $model;
        $this->template = $template;
        $this->html = $template;
    }

    public function issetParseData(){

        $isset = false;

        //проверим надо ли ещё раз запускать
        if (preg_match('~{{(.*?)}}~', $this->html)) {$isset = true;}

        //if(preg_match('~\[(\[|\!)(.*?)(\!|\])\]~ms', $this->html)){$isset = true;}

        //if (preg_match('~\[\*(.*?)\*\]~', $this->html)) {$isset = true;}

        return $isset;
    }

    public function run(){

        $this->parseMetaTagsPage();

        $this->parseChunk();

        $this->parseSnippet();

        //проверим надо ли запускать чанки,сниппеты и т.д. по странице
        if($this->issetParseData()){
            $this->run();
        }
    }

    /*
     * парсим сниппеты
     */
    public function parseSnippet(){

        //$this->phxSnippet();

        //$this->html = preg_replace('~\[\[(.*?)\]\]~', '', $this->html);
        //$this->html = preg_replace('~\[\!(.*?)\!\]~', '', $this->html);
        preg_match_all('~\[(\[|\!)(.*?)(\!|\])\]~ms', $this->html, $matches);

        //$matches[2]-список вызовов всех сниппетов на странице(выхов без скобочек)
        //$matches[0]-вызов сниппета как в тексте страницы, используем его для замены на результат работы сниппета
        //echo '<pre>'; print_r($matches[2]);die();

        $matches_main = array();
        $replace = array();
        foreach($matches[2] as $index=>$snippet){

            //ищем вызов сниппет
            if(preg_match('/(\W|^)Ditto(\W|$)/',$snippet)){
                $ditto = new Ditto($this->model, $snippet);
                //разбираем параметры вызова сниппета - Дитто
                $ditto->parseString();
                //запишим на страницу результат работы сниппета
                //$this->html = str_replace($matches[0][$index], $ditto->result, $this->html);
                $matches_main[] = $matches[0][$index];
                $replace[] = $ditto->result;
            }
            // ищем вызов сниппета - Wayfinder

            if(preg_match('/(\W|^)Wayfinder(\W|$)/',$snippet)){
                $wayfinder = new Wayfinder($this->model, $snippet);
                //разбираем параметры вызова сниппета - Wayfinder
                $wayfinder->parseString();
                //запишим на страницу результат работы сниппета
                //$this->html = str_replace($matches[0][$index], $wayfinder->result, $this->html);
                $matches_main[] = $matches[0][$index];
                $replace[] = $wayfinder->result;
            }

            //нашли вызов сниппета - Хлебные крошки
            if(preg_match('/(\W|^)Breadcrumbs(\W|$)/i',$snippet)){
                $breadcrumbs = new Breadcrumbs($this->model);
                $matches_main[] = $matches[0][$index];
                $replace[] = $breadcrumbs->run();
            }
        }

        $this->html = str_replace($matches_main, $replace, $this->html);

        unset($matches_main);
        unset($replace);
    }


    /*
     * парсим код страницы на предмет вызова сниппета Phx
     * он всегда отрабатывает первым среди всех сниппетов
     */
    public function phxSnippet(){
        //find Phx snippet
        if(preg_match_all('/\[\+phx:(.*?)\+\]/',$this->html,$matches)){

            $find = array();
            $replace = array();

            foreach($matches[1] as $index=>$stringCall){
                $find[] = $matches[0][$index];
                $phx = new Phx($this->model,$stringCall);
                $phx->html = $this->html;
                $phx->action();
                $replace[] =  $phx->result;
            }
//            echo '<pre>'; print_r($matches[1]);
//            echo '<pre>'; print_r($replace);
//            die();

            $this->html = str_replace($find, $replace, $this->html);
            unset($find); unset($replace);
        }


    }


    /*
     * проставляем мета-теги, заголовок страницы, ключевые слова и т.д.
     */
    public function parseMetaTagsPage(){
        //[*pagetitle*]
        if (preg_match_all('~\[\*(.*?)\*\]~', $this->html, $matches)) {
            //echo '<pre>'; print_r($matches);
            foreach($matches[1] as $param){
                //заменяем вызов чанка его содержимым
                if(!empty($this->model->{$param})){
                    $this->html = str_replace('[*'.$param.'*]', $this->model->{$param}, $this->html);
                }else{
                    if(!empty($this->model->tv[$param])){
                        $this->html = str_replace('[*'.$param.'*]', $this->model->tv[$param], $this->html);
                    }else{
                        $this->html = str_replace('[*'.$param.'*]', '', $this->html);
                    }
                }
            }
        }
    }

    /*
     * поиск вызова чанков в коде
     */
    public function parseChunk(){
        $replace= array ();
        $matches_main= array ();

        if (preg_match_all('~{{(.*?)}}~', $this->html, $matches)) {
            //echo '<pre>'; print_r($matches);
            foreach($matches[1] as $title_chunk){
                //заменяем вызов чанка его содержимым
                $matches_main[] = '{{'.$title_chunk.'}}';
                $replace[] = Chunk::findChunkByName($title_chunk);
                //$this->html = str_replace('{{'.$title_chunk.'}}', Chunk::findChunkByName($title_chunk), $this->html);
            }

            $this->html = str_replace($matches_main, $replace, $this->html);
            unset($matches_main);unset($replace);
        }
    }

    /*
     * заменяем все вызовы тв-параметров
     */
    static function mergeTvParamsContent($html, $model){

        if (preg_match_all('~\[(\*|\+)(.*?)(\*|\+)\]~', $html, $matches)) {
            //echo '<pre>'; print_r($matches);die();
            foreach($matches[2] as $param){
                //заменяем вызов чанка его содержимым
                if(!empty($model->{$param})){
                    $html = str_replace(array('[*'.$param.'*]', '[+'.$param.'+]'), $model->{$param}, $html);
                }else{
                    if(!empty($model->tv[$param])){
                       $html = str_replace(array('[*'.$param.'*]', '[+'.$param.'+]'), $model->tv[$param], $html);
                    }else{
                      $html = str_replace(array('[*'.$param.'*]', '[+'.$param.'+]'), '', $html);
                    }
                }

                //$this->html = str_replace('[*'.$param.'*]', $this->model->tv->{$param}, $this->html);
            }
            //заглушка - замена ссылок - [~18~]
            $html = str_replace('[~'.$model->id.'~]', '/?r=site/index&alias='.$model->alias, $html);
        }

        return $html;
    }

    static function mergeChunkContent($html){
        $replace= array ();
        $matches= array ();
        if (preg_match_all('~{{(.*?)}}~', $html, $matches)) {
            //echo '<pre>'; print_r($matches);
            foreach($matches[1] as $title_chunk){
                //заменяем вызов чанка его содержимым
                $html = str_replace('{{'.$title_chunk.'}}', Chunk::findChunkByName($title_chunk), $html);
            }
        }

        return $html;
    }

    /**
     * name: parseDocumentSource - used by parser
     * desc: return document source aftering parsing tvs, snippets, chunks, etc.
     */
    /*
    function parseDocumentSource($source) {
        // set the number of times we are to parse the document source
        $this->minParserPasses= empty ($this->minParserPasses) ? 2 : $this->minParserPasses;
        $this->maxParserPasses= empty ($this->maxParserPasses) ? 10 : $this->maxParserPasses;
        $passes= $this->minParserPasses;
        for ($i= 0; $i < $passes; $i++) {
            // get source length if this is the final pass
            if ($i == ($passes -1))
                $st= strlen($source);
            if ($this->dumpSnippets == 1) {
                echo "<fieldset><legend><b style='color: #821517;'>PARSE PASS " . ($i +1) . "</b></legend>The following snippets (if any) were parsed during this pass.<div style='width:100%' align='center'>";
            }

            // invoke OnParseDocument event
            $this->documentOutput= $source; // store source code so plugins can
            $this->invokeEvent("OnParseDocument"); // work on it via $modx->documentOutput
            $source= $this->documentOutput;

            // combine template and document variables
            $source= $this->mergeDocumentContent($source);
            // replace settings referenced in document
            $source= $this->mergeSettingsContent($source);
            // replace HTMLSnippets in document
            $source= $this->mergeChunkContent($source);
            // insert META tags & keywords
            $source= $this->mergeDocumentMETATags($source);
            // find and merge snippets
            $source= $this->evalSnippets($source);
            // find and replace Placeholders (must be parsed last) - Added by Raymond
            $source= $this->mergePlaceholderContent($source);
            if ($this->dumpSnippets == 1) {
                echo "</div></fieldset><br />";
            }
            if ($i == ($passes -1) && $i < ($this->maxParserPasses - 1)) {
                // check if source length was changed
                $et= strlen($source);
                if ($st != $et)
                    $passes++; // if content change then increase passes because
            } // we have not yet reached maxParserPasses
        }
        return $source;
    }*/
} 