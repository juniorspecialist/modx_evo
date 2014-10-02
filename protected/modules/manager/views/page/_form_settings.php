<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24.09.14
 * Time: 17:36
 */
?>

<div class="row">
    <?php echo $form->label($model,'pub_date'); ?>
    <?php
    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
        'name'=>'pub_date',
        'model'=>$model,
        'attribute'=>'pub_date',
        // additional javascript options for the date picker plugin
        'options'=>array(
            'showAnim'=>'fold',
        ),
        'htmlOptions'=>array(
            'style'=>'height:20px;'
        ),
    ));
    ?>
</div>


<div class="row">
    <?php echo $form->labelEx($model,'menuindex'); ?>
    <?php echo $form->textField($model,'menuindex'); ?>
    <?php echo $form->error($model,'menuindex'); ?>
</div>

<div class="row">
    <?php echo $form->checkBox($model,'published'); ?>
    <?php echo $form->label($model,'published'); ?>
</div>

<div class="row">
    <?php echo $form->checkBox($model,'searchable'); ?>
    <?php echo $form->label($model,'searchable'); ?>
</div>

<div class="row">
    <?php echo $form->checkBox($model,'cacheable'); ?>
    <?php echo $form->label($model,'cacheable'); ?>
</div>


<div class="row">
    <?php echo $form->checkBox($model,'hidemenu'); ?>
    <?php echo $form->label($model,'hidemenu'); ?>
</div>



