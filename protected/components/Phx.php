<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11.08.14
 * Time: 20:12
 */

class Phx extends Ditto {

    public $if;
    public $is;
    public $else;
    public $then;
    public $html;

    public $safetags = array();

    // Parser: modifier detection and eXtended processing if needed
    function Filter($input, $modifiers) {
        global $modx;
        $output = $input;
        $this->Log("  |--- Input = '". $output ."'");
        if (preg_match_all('~:([^:=]+)(?:=`(.*?)`(?=:[^:=]+|$))?~s',$modifiers, $matches)) {
            $modifier_cmd = $matches[1]; // modifier command
            $modifier_value = $matches[2]; // modifier value
            $count = count($modifier_cmd);
            $condition = array();
            for($i=0; $i<$count; $i++) {
                $output = trim($output);
                $this->Log("  |--- Modifier = '". $modifier_cmd[$i] ."'");
                if ($modifier_value[$i] != '') $this->Log("  |--- Options = '". $modifier_value[$i] ."'");
                switch ($modifier_cmd[$i]) {
                    #####  Conditional Modifiers
                    case "input":	case "if": $output = $modifier_value[$i]; break;
                    case "equals": case "is": case "eq": $condition[] = intval(($output==$modifier_value[$i])); break;
                    case "notequals": case "isnot":	case "isnt": case "ne":$condition[] = intval(($output!=$modifier_value[$i]));break;
                    case "isgreaterthan":	case "isgt": case "eg": $condition[] = intval(($output>=$modifier_value[$i]));break;
                    case "islowerthan": case "islt": case "el": $condition[] = intval(($output<=$modifier_value[$i]));break;
                    case "greaterthan": case "gt": $condition[] = intval(($output>$modifier_value[$i]));break;
                    case "lowerthan":	case "lt":$condition[] = intval(($output<$modifier_value[$i]));break;
                    case "isinrole": case "ir": case "memberof": case "mo": // Is Member Of  (same as inrole but this one can be stringed as a conditional)
                    if ($output == "&_PHX_INTERNAL_&") $output = $this->user["id"];
                    $grps = (strlen($modifier_value) > 0 ) ? explode(",",$modifier_value[$i]) :array();
                    $condition[] = intval($this->isMemberOfWebGroupByUserId($output,$grps));
                    break;
                    case "or":$condition[] = "||";break;
                    case "and":	$condition[] = "&&";break;
                    case "show":
                        $conditional = implode(' ',$condition);
                        $isvalid = intval(eval("return (". $conditional. ");"));
                        if (!$isvalid) { $output = NULL;}
                    case "then":
                        $conditional = implode(' ',$condition);
                        $isvalid = intval(eval("return (". $conditional. ");"));
                        if ($isvalid) { $output = $modifier_value[$i]; }
                        else { $output = NULL; }
                        break;
                    case "else":
                        $conditional = implode(' ',$condition);
                        $isvalid = intval(eval("return (". $conditional. ");"));
                        if (!$isvalid) { $output = $modifier_value[$i]; }
                        break;
                    case "select":
                        $raw = explode("&",$modifier_value[$i]);
                        $map = array();
                        for($m=0; $m<(count($raw)); $m++) {
                            $mi = explode("=",$raw[$m]);
                            $map[$mi[0]] = $mi[1];
                        }
                        $output = $map[$output];
                        break;
                    ##### End of Conditional Modifiers

                    #####  String Modifiers
                    case "lcase": case "strtolower": $output = strtolower($output); break;
                    case "ucase": case "strtoupper": $output = strtoupper($output); break;
                    case "htmlent": case "htmlentities": $output = htmlentities($output,ENT_QUOTES,$modx->config['etomite_charset']); break;
                    case "html_entity_decode": $output = html_entity_decode($output,ENT_QUOTES,$modx->config['etomite_charset']); break;
                    case "esc":
                        $output = preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", htmlspecialchars($output));
                        $output = str_replace(array("[","]","`"),array("&#91;","&#93;","&#96;"),$output);
                        break;
                    case "strip": $output = preg_replace("~([\n\r\t\s]+)~"," ",$output); break;
                    case "notags": case "strip_tags": $output = strip_tags($output); break;
                    case "length": case "len": case "strlen": $output = strlen($output); break;
                    case "reverse": case "strrev": $output = strrev($output); break;
                    case "wordwrap": // default: 70
                        $wrapat = intval($modifier_value[$i]) ? intval($modifier_value[$i]) : 70;
                        $output = preg_replace("~(\b\w+\b)~e","wordwrap('\\1',\$wrapat,' ',1)",$output);
                        break;
                    case "limit": // default: 100
                        $limit = intval($modifier_value[$i]) ? intval($modifier_value[$i]) : 100;
                        $output = substr($output,0,$limit);
                        break;
                    case "str_shuffle": case "shuffle":	$output = str_shuffle($output); break;
                    case "str_word_count": case "word_count":	case "wordcount": $output = str_word_count($output); break;

                    // These are all straight wrappers for PHP functions
                    case "ucfirst":
                    case "lcfirst":
                    case "ucwords":
                    case "addslashes":
                    case "ltrim":
                    case "rtrim":
                    case "trim":
                    case "nl2br":
                    case "md5": $output = $modifier_cmd[$i]($output); break;


                    #####  Special functions
                    case "math":
                        $filter = preg_replace("~([a-zA-Z\n\r\t\s])~","",$modifier_value[$i]);
                        $filter = str_replace("?",$output,$filter);
                        $output = eval("return ".$filter.";");
                        break;
                    case "ifempty": if (empty($output)) $output = $modifier_value[$i]; break;
                    case "date": $output = strftime($modifier_value[$i],0+$output); break;
                    case "set":
                        $c = $i+1;
                        if ($count>$c&&$modifier_cmd[$c]=="value") $output = preg_replace("~([^a-zA-Z0-9])~","",$modifier_value[$i]);
                        break;
                    case "value":
                        if ($i>0&&$modifier_cmd[$i-1]=="set") { $modx->SetPlaceholder("phx.".$output,$modifier_value[$i]); }
                        $output = NULL;
                        break;
                    case "userinfo":
                        if ($output == "&_PHX_INTERNAL_&") $output = $this->user["id"];
                        $output = $this->ModUser($output,$modifier_value[$i]);
                        break;
                    case "inrole": // deprecated
                        if ($output == "&_PHX_INTERNAL_&") $output = $this->user["id"];
                        $grps = (strlen($modifier_value) > 0 ) ? explode(",",$modifier_value[$i]) :array();
                        $output = intval($this->isMemberOfWebGroupByUserId($output,$grps));
                        break;

                    // If we haven't yet found the modifier, let's look elsewhere
                    default:

                        // Is a snippet defined?
                        if (!array_key_exists($modifier_cmd[$i], $this->cache["cm"])) {
                            $sql = "SELECT snippet FROM " . $modx->getFullTableName("site_snippets") . " WHERE " . $modx->getFullTableName("site_snippets") . ".name='phx:" . $modifier_cmd[$i] . "';";
                            $result = $modx->dbQuery($sql);
                            if ($modx->recordCount($result) == 1) {
                                $row = $modx->fetchRow($result);
                                $cm = $this->cache["cm"][$modifier_cmd[$i]] = $row["snippet"];
                                $this->Log("  |--- DB -> Custom Modifier");
                            } else if ($modx->recordCount($result) == 0){ // If snippet not found, look in the modifiers folder
                                $filename = $modx->config['rb_base_dir'] . 'plugins/phx/modifiers/'.$modifier_cmd[$i].'.phx.php';
                                if (@file_exists($filename)) {
                                    $file_contents = @file_get_contents($filename);
                                    $file_contents = str_replace('<?php', '', $file_contents);
                                    $file_contents = str_replace('?>', '', $file_contents);
                                    $file_contents = str_replace('<?', '', $file_contents);
                                    $cm = $this->cache["cm"][$modifier_cmd[$i]] = $file_contents;
                                    $this->Log("  |--- File ($filename) -> Custom Modifier");
                                } else {
                                    $cm = '';
                                    $this->Log("  |--- PHX Error:  {$modifier_cmd[$i]} could not be found");
                                }
                            }
                        } else {
                            $cm = $this->cache["cm"][$modifier_cmd[$i]];
                            $this->Log("  |--- Cache -> Custom Modifier");
                        }
                        ob_start();
                        $options = $modifier_value[$i];
                        $custom = eval($cm);
                        $msg = ob_get_contents();
                        $output = $msg.$custom;
                        ob_end_clean();
                        break;
                }
                if (count($condition)) $this->Log("  |--- Condition = '". $condition[count($condition)-1] ."'");
                $this->Log("  |--- Output = '". $output ."'");
            }
        }
        return $output;
    }

    public function _t(){
        // PHx / MODx Tags
        if ( preg_match_all('~\[(\+|\*|\()([^:\+\[\]]+)([^\[\]]*?)(\1|\))\]~s',$this->html, $matches)) {

            echo '<pre>'; print_r($matches); die();

            //$matches[0] // Complete string that's need to be replaced
            //$matches[1] // Type
            //$matches[2] // The placeholder(s)
            //$matches[3] // The modifiers
            //$matches[4] // Type (end character)

            $count = count($matches[0]);
            $var_search = array();
            $var_replace = array();
            for($i=0; $i<$count; $i++) {
                $replace = NULL;
                $match = $matches[0][$i];
                $type = $matches[1][$i];
                $type_end = $matches[4][$i];
                $input = $matches[2][$i];
                $modifiers = $matches[3][$i];
                $var_search[] = $match;
                switch($type) {
                    // Document / Template Variable eXtended
                    case "*":
                        //$input = $modx->mergeDocumentContent("[*".$input."*]");
                        $replace = $this->Filter($input,$modifiers);
                        break;
                    // MODx Setting eXtended
                    case "(":
                        die("[(".$input.")]");
                        //$input = $modx->mergeSettingsContent("[(".$input.")]");
                        $replace = $this->Filter($input,$modifiers);
                        break;
                    // MODx Placeholder eXtended
                    default:
                        // Check if placeholder is set
                        die('modx->placeholders)');
//                        if ( !array_key_exists($input, $this->placeholders) && !array_key_exists($input, $modx->placeholders) ) {
//                            // not set so try again later.
//                            $replace = $match;
//                        }
//                        else {
//                            // is set, get value and run filter
//                            $input = $this->getPHxVariable($input);
//                            $replace = $this->Filter($input,$modifiers);
//                        }
                        break;
                }
                $var_replace[] = $replace;
            }
            $this->html = str_replace($var_search, $var_replace, $this->html);
        }
    }

    /*
     * парсим строку вызова сниппета Phx
     */
    public function parseString(){


        $this->safetags[0][0] = '~(?<![\[]|^\^)\[(?=[^\+\*\(\[]|$)~s';
        $this->safetags[0][1] = '~(?<=[^\+\*\)\]]|^)\](?=[^\]]|$)~s';
        $this->safetags[1][0] = '&_PHX_INTERNAL_091_&';
        $this->safetags[1][1] = '&_PHX_INTERNAL_093_&';
        $this->safetags[2][0] = '[';
        $this->safetags[2][1] = ']';


        //несколько вариантов вызова сниппета

        $this->html = preg_replace($this->safetags[0],$this->safetags[1],$this->html);


        $this->html = $this->_t($this->html);

        preg_match_all('~\[(\+|\*|\()([^:\+\[\]]+)([^\[\]]*?|)(\1|\))\]~s', $this->html, $matches);
        if ($matches[0]) {
            $this->html = str_replace($matches[0], '', $this->html);
            //$this->Log("Cleaning unsolved tags: \n" . implode("\n",$matches[2]) );
        }

        // Restore non-call characters in the template: [, ]
        $this->html = str_replace($this->safetags[1],$this->safetags[2],$this->html);
        // Set template post-process hash
        //$et = md5($this->html);
        // If template has changed, parse it once more...
        //$template = $this->Parse($this->html);

        die($this->html);


        if (preg_match_all('~\[(\[|\!)(.*?)(\]|\!)\]~s',$this->callString, $matches)) {
            echo $this->callString.'<br>';
            echo '<pre>'; print_r($matches);
        }


        //==========================================================================================




//        $callParams = explode(':', $this->callString);
//        foreach($callParams as $index=>$param){
//
//            if(preg_match('/=/',$param)){
//
//                $expl_data =explode('=', $param);
//
//                $this->{$expl_data[0]} = $expl_data[1];
//            }
//        }

//        echo 'if='.$this->if.'<br>';
//        echo 'is='.$this->is.'<br>';
//        echo 'else='.$this->else.'<br>';
//        echo 'then'.$this->then.'<br>';

        //echo '<pre>'; print_r($str);// die();


        /*
        if(preg_match('/\[\+phx:/i',$this->callString)){
            //строка вызова пример - [+phx:if=`Hyundai`:is=``:then=``:else=``+] [+phx:if=`сплит-система`:is=``:then=``:else=``+]
            $this->callString = str_replace(array('[+phx:','+] '), '', $this->callString);
            //echo $this->callString.'<br>';
            //обработка значения IF
            if(preg_match('/if=`(.*?)`/i', $this->callString, $if_list)){
                //echo '<pre>'; print_r($if_list);
                //if_list[1] - значение параметра для условия
                //определим какой тип значения указан
                //[*pagetitle*]-тип значения
                if (preg_match_all('~\[\*(.*?)\*\]~', $if_list[1], $matches)) {
                    //echo '<pre>'; print_r($matches);
                    foreach($matches[1] as $param){
                        //заменяем вызов чанка его содержимым
                        if(!empty($this->model->{$param})){
                            $this->if = str_replace('[*'.$param.'*]', $this->model->{$param}, $if_list[1]);
                        }else{
                            if(!empty($this->model->tv[$param])){
                                $this->if = str_replace('[*'.$param.'*]', $this->model->tv[$param], $if_list[1]);
                            }else{
                                $this->if = str_replace('[*'.$param.'*]', '', $if_list[1]);
                            }
                        }

                        $this->html = str_replace('[*'.$param.'*]', $this->model->tv->{$param}, $this->html);
                    }
                }

                //echo 'if='.$this->if.'<br>';
            }
        }

        //другой случай, когда вызов сниппета-[*isfolder:is=`1`:then=`wara<br>`:is=``:then=`CO`:else=`COM 350/ 03`+]
        if(preg_match('/\[\*(.*?):/i',$this->callString,$matches)){
            echo '<pre>'; print_r($matches); die();
            $this->callString = str_replace('+]', '', $this->callString);
            echo ($this->callString);
            $this->callString = preg_replace('/\[\*(.*?):/','',$this->callString);
            if (preg_match_all('~\[\*(.*?)\*\]~', $this->callString, $matches)) {

                foreach($matches[1] as $param){

                    //$

                    //заменяем вызов чанка его содержимым
                    if(!empty($this->model->{$param})){
                        $this->if = str_replace('[*'.$param.'*]', $this->model->{$param}, $this->callString);
                    }else{
                        if(!empty($this->model->tv[$param])){
                            $this->if = str_replace('[*'.$param.'*]', $this->model->tv[$param], $this->callString);
                        }else{
                            $this->if = str_replace('[*'.$param.'*]', '', $if_list[1]);
                        }
                    }

                    $this->html = str_replace('[*'.$param.'*]', $this->model->tv->{$param}, $this->html);
                }

                //echo $this->if.'<br>';
            }
        }

        //обработка значения IS
        if(preg_match('/is=`(.*?)`/i', $this->callString, $is_list)){
            //echo '<pre>'; print_r($if_list);
            //if_list[1] - значение параметра для условия
            //определим какой тип значения указан
            //[*pagetitle*]-тип значения
            if (preg_match_all('~\[\*(.*?)\*\]~', $is_list[1], $matches)) {
                //echo '<pre>'; print_r($matches);
                foreach($matches[1] as $param){
                    //заменяем вызов чанка его содержимым
                    if(!empty($this->model->{$param})){
                        $this->is = str_replace('[*'.$param.'*]', $this->model->{$param}, $is_list[1]);
                    }else{
                        if(!empty($this->model->tv[$param])){
                            $this->is = str_replace('[*'.$param.'*]', $this->model->tv[$param], $is_list[1]);
                        }else{
                            $this->is = str_replace('[*'.$param.'*]', '', $is_list[1]);
                        }
                    }

                    $this->html = str_replace('[*'.$param.'*]', $this->model->tv->{$param}, $this->html);
                }
            }

            //echo 'is='.$this->is.'<br>';
        }

        //обработка значения THEN
        if(preg_match('/then=`(.*?)`/i', $this->callString, $then_list)){
            //echo '<pre>'; print_r($if_list);
            //if_list[1] - значение параметра для условия
            //определим какой тип значения указан
            //[*pagetitle*]-тип значения
            if (preg_match_all('~\[\*(.*?)\*\]~', $then_list[1], $matches)) {
                //echo '<pre>'; print_r($matches);
                foreach($matches[1] as $param){
                    //заменяем вызов чанка его содержимым
                    if(!empty($this->model->{$param})){
                        $this->then = str_replace('[*'.$param.'*]', $this->model->{$param}, $then_list[1]);
                    }else{
                        if(!empty($this->model->tv[$param])){
                            $this->then= str_replace('[*'.$param.'*]', $this->model->tv[$param], $then_list[1]);
                        }else{
                            $this->then = str_replace('[*'.$param.'*]', '', $then_list[1]);
                        }
                    }

                    $this->html = str_replace('[*'.$param.'*]', $this->model->tv->{$param}, $this->html);
                }
            }

            //echo 'then='.$this->then.'<br>';
        }

        //обработка значения ELSE
        if(preg_match('/else=`(.*?)`/i', $this->callString, $else_list)){
            //echo '<pre>'; print_r($if_list);
            //if_list[1] - значение параметра для условия
            //определим какой тип значения указан
            //[*pagetitle*]-тип значения
            if (preg_match_all('~\[\*(.*?)\*\]~', $else_list[1], $matches)) {
                //echo '<pre>'; print_r($matches);
                foreach($matches[1] as $param){
                    //заменяем вызов чанка его содержимым
                    if(!empty($this->model->{$param})){
                        $this->else = str_replace('[*'.$param.'*]', $this->model->{$param}, $else_list[1]);
                    }else{
                        if(!empty($this->model->tv[$param])){
                            $this->else= str_replace('[*'.$param.'*]', $this->model->tv[$param], $else_list[1]);
                        }else{
                            $this->else = str_replace('[*'.$param.'*]', '', $else_list[1]);
                        }
                    }

                   $this->html = str_replace('[*'.$param.'*]', $this->model->tv->{$param}, $this->html);
                }
            }

            //echo 'else='.$this->else.'<br>';
        }

        */
    }
    /*
       * список перменных получили, обработка входящих параметров и вывод реультата
       */
    public function action(){

        // парсим строку вызова сниппета и назначаем параметры для обработки
        $this->parseString();

        //обработка полученных параметров и вывод результата
        if(trim($this->if)==trim($this->is)){
            $this->result = $this->then;
        }else{
            $this->result = $this->else;
        }
    }

}