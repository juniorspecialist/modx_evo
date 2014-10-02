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

</div><!-- form -->
<?php


// flush cache
//YiiBase::app()->cache->delete('settings_');
//
//// add data to cache
//Yii::app()->cache->set('apple', 'fruit');
//Yii::app()->cache->set('onion', 'vegetables');
//Yii::app()->cache->set(1, 'one');
//Yii::app()->cache->set(2, 'two');
//Yii::app()->cache->set('one', 1);
//Yii::app()->cache->set('two', 2);
//
//// delete from cache
//Yii::app()->cache->delete(1);
//Yii::app()->cache->delete('two');
//
//// read from cache
//echo Yii::app()->cache->get(2);


//echo Yii::app()->config->get('BLOG.POSTS_PER_PAGE');

//Yii::app()->config->add(array(
//    'param'=>'SYSTEM.DEFAULT_TEMPLATE',
//    'label'=>'Шаблон по умолчанию',
//    'value'=>'92',
//    'type'=>'dropdown',
//    'default'=>'92',
//));
//
////
//Yii::app()->config->add(array(
//    'param'=>'SYSTEM.ACCESS_DENY',
//    'label'=>"Страница 'Доступ запрещен'",
//    'value'=>'744',
//    'type'=>'text',
//    'default'=>'744',
//));
//
//
//Yii::app()->config->add(array(
//    'param'=>'SYSTEM.NOT_FIND_PAGE',
//    'label'=>"Страница ошибки '404'",
//    'value'=>'744',
//    'type'=>'text',
//    'default'=>'744',
//));

//Yii::app()->config->add(array(
//    'param'=>'SYSTEM.MAIN_PAGE',
//    'label'=>"ID главной страницы",
//    'value'=>'1',
//    'type'=>'text',
//    'default'=>'1',
//));


//echo Yii::app()->config->get('BLOG.POSTS_PER_PAGE1');