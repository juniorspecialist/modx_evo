<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.09.14
 * Time: 14:23
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
        '_id',
//        array(
//            'type'=>'raw',
//            'value'=>'$data->_id',
//            'name'=>'_id',
//            'header'=>'ID',
//        ),
        'pagetitle',
        'alias',
        //'create_time',
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}',
        ),
    ),
));