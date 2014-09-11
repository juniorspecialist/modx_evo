<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>



<div class="form" >
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Поля с <span class="required">*</span> обязательны к заполнению.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
<!--		<p class="hint">-->
<!--			Hint: You may login with <kbd>demo</kbd>/<kbd>demo</kbd> or <kbd>admin</kbd>/<kbd>admin</kbd>.-->
<!--		</p>-->
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<div class="row buttons">
		<?php echo TbHtml::submitButton('Авторизоваться');?>
	</div>

<?php $this->endWidget(); ?>
    <div id="treecontrol" style="display: none;">
        <a href="#">Collapse All</a> |
        <a href="#">Expand All</a>
    </div>
    <?php


    $this->widget('CTreeView',array(
        'id'=>'menu-treeview',
        'url'=>array('tree/fillTree'),
        //'data'=>$dataTree,
        //'control'=>'#treecontrol',
        //'animated'=>'fast',
        'collapsed'=>true,
        'unique'=>false,
        'persist'=>'cookie',
        'htmlOptions'=>array(
            'class'=>'filetree'
        )
    ));

    ?>

</div><!-- form -->
