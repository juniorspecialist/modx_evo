<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.09.14
 * Time: 14:15
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />

    <!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
    <![endif]-->

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

    <?php Yii::app()->bootstrap->register(); ?>

<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body >

<div class="container1" id="page1">

<!--    <div class="span-5" style="margin-left:0px;height: 100%; overflow: auto;">-->
<!--        <strong>Дерево документов</strong>-->
<!--        <div id="sidebar" style=" height: auto; overflow: auto;">-->
<!--            --><?php
//            $this->widget('CTreeView',array(
//                'id'=>'menu-treeview',
//                'url'=>array('/manager/tree/fillTree'),
//                //'data'=>$dataTree,
//                //'control'=>'#treecontrol',
//                //'animated'=>'fast',
//                'collapsed'=>true,
//                'unique'=>false,
//                //'persist'=>'cookie',
//                'htmlOptions'=>array(
//                    'class'=>'filetree'
//                )
//            ));
//            ?>
<!---->
<!--        </div>-->
<!--    </div>-->
<!--    <iframe name="main" src="/manager/menu/" scrolling="no" frameborder="0" style="margin-left:0px;height: 1000px; width: 80%;"></iframe>-->
    <?php

    ?>
        <?php echo $content; ?>

    <?php ?>


</div><!-- page -->

<script type="text/javascript">
    $(function() {
        $(document).on('click','#hide_tree',function(event){
            event.preventDefault();
            //скрываем фрем с деревом
            $('#tree_iframe').hide();
            //показываем ссылку для отображения дерева
            $('#show_tree').show();
            //прячем текущую ссылку(для скрывания дерева)
            $('#hide_tree').hide();
            //фрейм-контента - расширяем
            $('#main_frame').css('width','95%');
        });
        $(document).on('click','#show_tree',function(event){
            event.preventDefault();
            //показывам фрейм-дерева
            $('#tree_iframe').show();
            //прячем кнопку - показа дерева
            $('#show_tree').hide();
            //уменьшаем фрейм-основного содержимого
            $('#main_frame').css('width','79%');
            //показываем ссылку на скрывание дерева
            $('#hide_tree').show();
        });

    })
</script>

</body>
</html>