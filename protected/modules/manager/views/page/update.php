<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.09.14
 * Time: 10:17
 */
?>



    <h2>Редактирование документа <?php echo $model->_id;?></h2>

<?php $this->renderPartial('_form', array('model'=>$model, 'tv'=>$tv)); ?>