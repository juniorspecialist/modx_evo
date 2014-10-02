<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16.09.14
 * Time: 21:53
 */
?>
    <h2>Редактировать шаблон: <?php echo $model->title?></h2>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>