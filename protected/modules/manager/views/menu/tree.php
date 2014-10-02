<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16.09.14
 * Time: 21:35
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

    <body>

        <div id="treecontrol" style="display: none;"><a href="#">Collapse All</a> |<a href="#">Expand All</a></div>
        <?php
        $this->widget('CTreeView',array(
            'id'=>'menu-treeview',
            'url'=>array('/manager/tree/fillTree'),
            //'data'=>$dataTree,
            //'control'=>'#treecontrol',
            //'animated'=>'fast',
            'collapsed'=>true,
            'unique'=>false,
            //'persist'=>'cookie',
            'htmlOptions'=>array(
                'class'=>'filetree'
            )
        ));
        ?>
    </body>
</html>