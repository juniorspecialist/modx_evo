<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.09.14
 * Time: 23:54
 */
?>

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'chunk-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>true,
    )); ?>

    <?php echo $form->errorSummary($model); ?>


    <div class="row">
        <?php echo $form->label($model,'caption') ?>
        <?php echo $form->textField($model,'caption') ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'name') ?>
        <?php echo $form->textArea($model,'name') ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'description') ?>
        <?php echo $form->textArea($model,'description') ?>
    </div>


    <div class="row">
        <?php echo $form->label($model,'type') ?>
        <?php echo $form->dropDownList( $model,'type',Tv::getTypeList());?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'elements') ?>
        <?php echo $form->textArea($model,'elements', array('cols'=>10, 'rows'=>5, 'style'=>'width:50%; padding:5px; overflow:auto')) ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'default_text') ?>
        <?php echo $form->textArea($model,'default_text', array('cols'=>10, 'rows'=>5, 'style'=>'width:50%; padding:5px; overflow:auto')) ?>
    </div>

    <div class="row" id="templates_access">
        <h3>Доступ шаблонов:</h3><br>
        <?php
        echo CHtml::checkBoxList('access_tpl', $model->getTemplatesByTvParam(), Template::getTemplateList(),array('template'=>'{input} {label}<br>'));
        ?>
    </div>

    <div class="row buttons">
        <?php echo TbHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>

    </div>


    <?php $this->endWidget(); ?>

</div><!-- form -->