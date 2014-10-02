<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.09.14
 * Time: 15:48
 */
?>
    <h2>Редактировать чанк: <?php echo $model->title?></h2>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>