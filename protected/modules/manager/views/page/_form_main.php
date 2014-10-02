<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24.09.14
 * Time: 17:27
 */
/*
  * список полей общих реквизитов, которые у всех документов есть
  */
?>
<div class="row">
        <?php echo $form->labelEx($model,'pagetitle'); ?>
<?php echo $form->textField($model,'pagetitle', array('style'=>'width: 70%')); ?>
<?php echo $form->error($model,'pagetitle'); ?>
</div>
<div class="row">
    <?php echo $form->labelEx($model,'description'); ?>
    <?php echo $form->textArea($model,'description', array('style'=>'width: 70%')); ?>
    <?php echo $form->error($model,'description'); ?>
</div>
<div class="row">
    <?php echo $form->labelEx($model,'alias'); ?>
    <?php echo $form->textField($model,'alias', array('style'=>'width: 70%')); ?>
    <?php echo $form->error($model,'alias'); ?>
</div>
<div class="row">
    <?php echo $form->labelEx($model,'template'); ?>
    <?php echo $form->dropDownList( $model,'template',Template::getTemplateList(), array('style'=>'width: 70%'));?>
    <?php echo $form->error($model,'template'); ?>
</div>
<div class="row">
    <?php echo $form->labelEx($model,'menutitle'); ?>
    <?php echo $form->textField( $model,'menutitle', array('style'=>'width: 70%'));?>
    <?php echo $form->error($model,'menutitle'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model,'introtext'); ?>
    <?php echo $form->textArea( $model,'introtext', array('style'=>'width: 70%'));?>
    <?php echo $form->error($model,'introtext'); ?>
</div>