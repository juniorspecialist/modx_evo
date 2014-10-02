<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.09.14
 * Time: 13:34
 */

//namespace extensions\actions;


class XFillTreeAction extends CAction
{
    /**
     * @var string name of the model class.
     */
    public $modelName;
    /**
     * @var string name of the method of model class that returns data.
     */
    public $methodName='fillTree';
    /**
     * @var int id of the node that is taken as root node.
     */
    //если указать null то не будет показываться самый верхний элемент, 0-отображаем вверхний элемент дерева
    public $rootId=0;
    /**
     * @var bool wether the root node should be displayed.
     */
    public $showRoot=true;

    /**
     * Fills treeview based on the current user input.
     */
        public function run()
    {
        if(!isset($_GET['root'])||$_GET['root']=='source')
        {
            $rootId=$this->rootId;
            $showRoot=$this->showRoot;
        }
        else
        {
            $rootId=(int)$_GET['root'];
            $showRoot=false;
        }

        $dataTree=$this->getModel()->{$this->methodName}($rootId,$showRoot);
        echo CTreeView::saveDataAsJson($dataTree);
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