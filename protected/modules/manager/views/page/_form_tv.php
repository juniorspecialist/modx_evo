<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24.09.14
 * Time: 17:45
 */
/*
  * дополнительные поля - тв-параметры с их значениями по умолчанию- если новая запись
  * или с уже записанными значениями, если запись Не новая
  */

//массив HTML-элементов, каждый элемент это тв-параметр
if(!empty($tv)){
    //$element[0]-хтмл элемент, $element[1]- его название(label)
    foreach($tv as $element){
        ?>

        <div class="row">
            <?php echo $element[1]; ?>
            <?php echo $element[0]; ?>
        </div>
<?php

    }
}

?>
