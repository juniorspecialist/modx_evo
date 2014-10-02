<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.09.14
 * Time: 8:57
 */

$this->beginContent('//layouts/column_manager');

if(!YiiBase::app()->user->isGuest){
?>


    <div id="mainmenu1" >
        <?php
        if(!YiiBase::app()->user->isGuest){
            //TODO сделать меню виджетом отдельным
            echo TbHtml::tabs(array(
                array('label' => 'Документы', 'url' => '/manager/tree/',  'items' => array(
                    array('label' => 'Поиск', 'url' => '/manager/page/','target'=>'main','linkOptions'=>array('target'=>'main')),
                    array('label' => 'Добавить', 'url' => '/manager/page/create/','linkOptions'=>array('target'=>'main')),
                ),'active' => false,'target'=>'main'),

                array('label' => 'Шаблоны', 'items' => array(
                    array('label' => 'Поиск', 'url' => '/manager/template/','linkOptions'=>array('target'=>'main')),
                    array('label' => 'Добавить', 'url' => '/manager/template/create','linkOptions'=>array('target'=>'main')),
                )),

                array('label' => 'Чанки', 'items' => array(
                    array('label' => 'Поиск', 'url' => '/manager/chunk/','linkOptions'=>array('target'=>'main')),
                    array('label' => 'Добавить', 'url' => '/manager/chunk/create','linkOptions'=>array('target'=>'main')),
                )),

                array('label' => 'TV-параметры', 'items' => array(
                    array('label' => 'Поиск', 'url' => '/manager/tv/','linkOptions'=>array('target'=>'main')),
                    array('label' => 'Добавить', 'url' => '/manager/tv/create','linkOptions'=>array('target'=>'main')),
                )),

                array('label' => 'Модули', 'items' => array(
                    array('label' => 'Импорт', 'url' => '/manager/module/import','linkOptions'=>array('target'=>'main')),
                    array('label' => 'Экспорт', 'url' => '/manager/module/export','linkOptions'=>array('target'=>'main')),
                )),


                array('label' => 'Выход', 'url' => '/manager/login/logout','target'=>'main'),
            ));
        }
        ?>
    </div>

    <a href="#" id="hide_tree">Скрыть</a><a href="#" id="show_tree" style="display: none">Показать</a><br>
    <iframe id="tree_iframe" name="all" src="/manager/menu/tree" scrolling="yes" frameborder="0" style="margin-left:5px;height: 950px; width: 20%;"></iframe>

    <iframe id="main_frame" name="main" src="/manager/page/" scrolling="yes" frameborder="0" style="float: right; margin-left:0px;height: 950px; min-width: 79%; width: auto"></iframe>





<?php }else{?>
    <script type="text/javascript">
        //если УРЛ главного окна находится в авторизованной зоне - рефрешим страницу
        if(window.top.location.pathname=='/manager/tree'){
            window.top.location.reload();
        }
    </script>

    <div class="container1" id="page1">
        <div id="content"><?php echo $content; ?>
    </div>
<?php
    }

$this->endContent();?>