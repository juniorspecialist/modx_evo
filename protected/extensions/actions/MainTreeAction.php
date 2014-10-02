<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.09.14
 * Time: 14:15
 */
class MainTreeAction extends CAction{
    /**
     * @var string name of the model class.
     */
    public $modelName;
    /**
     * @var string name of the model attribute.
     */
    public $attributeName;

    /**
     * Runs the action.
     */
    public function run()
    {

        $this->getController()->renderPartial('_tree',array(),false, true);
        YiiBase::app()->end();
    }

    /**
     * @return CActiveRecord
     */
    protected function getModel()
    {
        return CActiveRecord::model($this->modelName);
    }
}