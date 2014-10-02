<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.09.14
 * Time: 14:50
 */
?>

<?php if(Yii::app()->user->hasFlash('msg')): ?>

    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('msg'); ?>
    </div>

<?php endif;


$this->widget('bootstrap.widgets.TbGridView', array(
    'id'=>'user-grid',
    'dataProvider'=>$model->searchD(),
    'filter'=>$model,
    'columns'=>array(
        //'id',
        'title',
        //'alias',
        //'create_time',
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}',
        ),
    ),
));