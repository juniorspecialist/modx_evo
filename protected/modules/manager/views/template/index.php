<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16.09.14
 * Time: 21:51
 */
$this->widget('bootstrap.widgets.TbGridView', array(
    'id'=>'user-grid',
    'dataProvider'=>$model->searchD(),
    'filter'=>$model,
    'columns'=>array(
        '_id',
        'title',

        //'create_time',
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}',
        ),
    ),
));