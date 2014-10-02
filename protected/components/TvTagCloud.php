<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.10.14
 * Time: 21:35
 */

/*
 * формируем список тегов для фильтрации товаров по выбранному тегу
 * чем-то похоже на категории но по указананным тв-параметрам
 */
class TvTagCloud extends Ditto{

    /*
     * парсим строку вызова сниппета
     */
    public function parseString(){

        echo '======================='.$this->callString.'============================<br>';

        die();
        //TODO
        if(preg_match('/phx(.*?)/i',$this->callString)){
            //распарсим параметры конструкции-условия
            //echo 'callString='.$this->callString.'<br>';
            $this->rule_if_else();
        }
    }

} 