<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.09.14
 * Time: 23:50
 */
$this->widget('bootstrap.widgets.TbGridView', array(
    'id'=>'tv-grid',
    'dataProvider'=>$model->searchD(),
    'filter'=>$model,
    'columns'=>array(
        'name',
        'caption',

        //'create_time',
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}',
        ),
    ),
));