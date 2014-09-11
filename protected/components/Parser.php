
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

    /*
     * проверяем есть ли вызовы тегов - сниппеты, чанки,тв-параметры
     * находим вызовы и заменяем их значениями
     */
    public function issetParseData($debug = false){

        $isset = false;

        //проверим надо ли ещё раз запускать
        if (preg_match('~{{(.*?)}}~', $this->html, $find)) {$isset = true;}

        if(preg_match('~\[(\[|\!)(.*?)(\!|\])\]~ms', $this->html, $find)){$isset = true;}

        //if (preg_match('~\[\*(.*?)\*\]~ms', $this->html, $find)) {$isset = true;}

        //if (preg_match('/\[\+(.*)\+\]/', $this->html, $find)) {$isset = true;}
        //[+phx:if=`[+artikul+]`:is=``:then=`CF 124 CSE`:else=`CF [+artikul+]`+]
        //if(preg_match('/\[\+(phx):(.*):(.*)\+\]/i',$this->html, $find)){  $isset = true;}

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

         preg_match_all('/\[(\[|\!|\+phx)(.*?)(\!|`\+|\])\]/mis', $this->html, $matches);

        $matches_main = array();
        $replace = array();
        foreach($matches[0] as $index=>$snippet){

            $replace[] = Parser::mergeSnippet($snippet, $this->model);//
            $matches_main[] = $matches[0][$index];
        }


        $this->html = str_replace($matches_main, $replace, $this->html);

        unset($matches_main);
        unset($replace);
    }

    /*
     * определяем вызов сниппета и запускаем его выполнение
     * определяем какой сниппет был вызван и потом запускаем сниппет на выполнение
     * $html - строка содержит вызов сниппета, определяем его вызов и что за сниппет
     */
    static function mergeSnippet($html, $model){

        $replace = '';//результат работы сниппета

        //ищем вызов сниппет
        if(preg_match('/(\W|^)Ditto(\W|$)/',$html)){
            $ditto = new Ditto($model, $html);
            //разбираем параметры вызова сниппета - Дитто
            $ditto->parseString();
            //запишим на страницу результат работы сниппета
            $replace = $ditto->result;
        }
        // ищем вызов сниппета - Wayfinder

        if(preg_match('/(\W|^)Wayfinder(\W|$)/',$html)){
            $wayfinder = new Wayfinder($model, $html);
            //разбираем параметры вызова сниппета - Wayfinder
            $wayfinder->parseString();
            //запишим на страницу результат работы сниппета
            $replace = $wayfinder->result;
        }

        //нашли вызов сниппета - Хлебные крошки
        if(preg_match('/(\W|^)Breadcrumbs(\W|$)/i',$html)){
            $breadcrumbs = new Breadcrumbs($model);
            //$matches_main[] = $matches[0][$index];
            $replace = $breadcrumbs->run();
        }

        //нашли вызов сниппета - PHX
        if(preg_match('/(.*?):(.*?)/',$html)){
            $phx = new Phx($model,$html);
            $phx->html = $html;
            $phx->action();
            $replace =  $phx->result;
        }

        return $replace;//возвращаем результат работы сниппета
    }

    /*
     * проставляем мета-теги, заголовок страницы, ключевые слова и т.д.
     */
    public function parseMetaTagsPage(){
        //[*pagetitle*]


        if (preg_match_all('~\[(\*|\+)(.*?)(\*|\+)\]~', $this->html, $matches)) {
           // echo '<pre>'; print_r($matches);
            foreach($matches[2] as $param){
                //заменяем вызов чанка его содержимым
                if(!empty($this->model->{$param})){
                    $this->html = str_replace(array('[*'.$param.'*]', '[+'.$param.'+]'), $this->model->{$param}, $this->html);
                }else{
                    if(!empty($this->model->tv[$param])){
                        $this->html = str_replace(array('[*'.$param.'*]', '[+'.$param.'+]'), $this->model->tv[$param], $this->html);
                    }else{
                        //$this->html = str_replace(array('[*'.$param.'*]', '[+'.$param.'+]'), '', $this->html);
                    }
                }
            }
        }

        //заглушка - заменяем значения некоторых параметров
        $this->html = str_replace('<base href="[(site_url)]" />', '<base href="'.YiiBase::app()->createAbsoluteUrl('').'/" />', $this->html);
        $this->html = str_replace('[*canonical*]', '', $this->html);
    }

    /*
     * поиск вызова чанков в коде
     */
    public function parseChunk(){

        $replace = array ();

        $matches_main= array ();

        if (preg_match_all('~{{(.*?)}}~', $this->html, $matches)) {
            //echo '<pre>'; print_r($matches);
            foreach($matches[1] as $title_chunk){
                //заменяем вызов чанка его содержимым
                $matches_main[] = '{{'.$title_chunk.'}}';
                $replace[] = Chunk::findChunkByName($title_chunk);
            }

            //вызов чанков заменяем их значениями
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
                      //$html = str_replace(array('[*'.$param.'*]', '[+'.$param.'+]'), '', $html);
                    }
                }

                //$this->html = str_replace('[*'.$param.'*]', $this->model->tv->{$param}, $this->html);
            }

            //заглушка - замена ссылок - [~18~]
            $html = str_replace('[~'.$model->id.'~]', Yii::app()->controller->createUrl('/site/index', array('alias'=>$model->alias)) , $html);


        }

        $html = str_replace('[+url+]', YiiBase::app()->controller->createAbsoluteUrl('/site/index', array('alias'=>$model->alias)) , $html);

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
} 