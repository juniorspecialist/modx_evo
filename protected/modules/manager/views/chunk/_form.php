<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.09.14
 * Time: 15:48
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
            <?php echo $form->label($model,'title') ?>
            <?php echo $form->textField($model,'title') ?>
        </div>

        <div class="row">
            <?php echo $form->label($model,'desc') ?>
            <?php echo $form->textArea($model,'desc') ?>
        </div>

        <div class="row">
            <?php echo $form->label($model,'content') ?>
            <?php echo $form->textArea($model,'content', array('cols'=>65, 'rows'=>25, 'style'=>'width:80%; padding:5px; overflow:auto')) ?>
        </div>

        <div class="row buttons">
        <?php echo TbHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
    </div>


<?php $this->endWidget(); ?>

</div><!-- form -->