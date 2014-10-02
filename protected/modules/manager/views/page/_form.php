<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.09.14
 * Time: 9:29
 */
?>
<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'page-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>true,
    )); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php
    /*$this->widget('bootstrap.widgets.TbTabs', array(
        'tabs' => array(
            array('label' => 'Home', 'content' => '', 'active' => true),
            array('label' => 'Profile', 'content' => $this->renderPartial('_form_main', array('model'=>$model, 'form'=>$form), false, false)),
            array('label' => 'Messages', 'items' => array(
                array('label' => '@fat', 'content' => '...'),
                array('label' => '@mdo', 'content' => '...'),
            )),
        ),
    ));*/

    $this->widget('zii.widgets.jui.CJuiTabs', array(
        'tabs'=>array(
            'Документ'=>$this->renderPartial('_form_main',array('model'=>$model, 'form'=>$form),true),
            'Настройки'=>$this->renderPartial('_form_settings',array('model'=>$model, 'form'=>$form),true),
            'Дополнительные параметры'=>$this->renderPartial('_form_tv',array('model'=>$model, 'form'=>$form, 'tv'=>$tv),true),
            //'Ajax tab'=>array('ajax'=>array('ajaxContent','view'=>'_content2')),
        ),
        'options'=>array(
            'collapsible'=>true,
            'selected'=>0,
        ),
        'htmlOptions'=>array(
            'style'=>'width:95%;'
        ),
    ));

    ?>

    <?php //отображаем общие параметры документа, всем документам присущи?>

     <div class="row">
        <?php echo $form->labelEx($model,'content'); ?>
        <?php



        $this->widget('ImperaviRedactorWidget', array(
            // You can either use it for model attribute
            'model' => $model,
            'attribute' => 'content',

            // or just for input field
            //'name' => 'my_input_name',

            // Some options, see http://imperavi.com/redactor/docs/
            'options' => array(
                'lang' => 'ru',
                'toolbar' => true,
                'iframe' => false,
                'minHeight'=> 500,
                //'maxHeight'=> 800,
                'toolbarFixed'=> true,
                //'css' => 'wym.css',
            ),

            'plugins' => array(
                'fullscreen' => array(
                    'js' => array('fullscreen.js',),
                ),
                'fontsize' => array(
                    'js' => array('fontsize.js',),
                ),
                'clips' => array(
                    'js' => array('clips.js',),
                ),
                'fontfamily' => array(
                    'js' => array('fontfamily.js',),
                ),
                'textdirection' => array(
                    'js' => array('textdirection.js',),
                ),

                'fontcolor' => array(
                    'js' => array('fontcolor.js',),
                ),

//                'clips' => array(
//                    // Можно указать путь для публикации
//                    'basePath' => 'application.components.imperavi.my_plugin',
//                    // Можно указать ссылку на ресурсы плагина, в этом случае basePath игнорирутеся.
//                    // По умолчанию, путь до папки plugins из ресурсов расширения
//                    'baseUrl' => '/js/my_plugin',
//                    'css' => array('clips.css',),
//                    'js' => array('clips.js',),
//                    // Можно также указывать зависимости
//                    'depends' => array('imperavi-redactor',),
//                ),
            ),
        ));

        ?>

    </div>


    <div class="row buttons">
        <?php echo TbHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('color' => TbHtml::BUTTON_COLOR_PRIMARY));?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->