<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.09.14
 * Time: 23:54
 */
?>
<h2>Редактировать тв-параметр: <?php echo $model->name?></h2>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
