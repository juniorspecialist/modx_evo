<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11.09.14
 * Time: 22:34
 */

class AdminController extends Controller{
    public $layout='//layouts/column_admin_tree';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return CMap::mergeArray(parent::filters(),array(
            'accessControl', // perform access control for CRUD operations
        ));
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                //'controllers'=>array('login'),
                'actions'=>array('login'),
                'users'=>array('*'),
                //'expression' => 'isset(Yii::app()->user->role) && (Yii::app()->user->role==='.Partner::ROLE_ADMIN.')',
            ),
            array('allow',  // allow all users to perform 'index' and 'view' actions
                //'actions'=>array('index','view'),
                'users'=>array('@'),
                //'expression' => 'isset(Yii::app()->user->role) && (Yii::app()->user->role=='.Partner::ROLE_ADMIN.')',
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
} 