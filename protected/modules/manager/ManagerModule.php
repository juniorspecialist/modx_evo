<?php

class ManagerModule extends CWebModule
{

    public $returnUrl = array("/manager/tree/");
    public $logoutUrl = array("/manager/login/logout");
    public $returnLogoutUrl = array("/manager/login/login");
    public $loginUrl = array("/manager/login/login");

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'manager.models.*',
			'manager.components.*',
            'manager.controllers.*',
            'application.extensions.*',
            'application.extensions.MongoYii.*',
            'application.extensions.MongoYii.validators.*',
            'application.extensions.MongoYii.behaviors.*',
            'application.extensions.MongoYii.util.*',
		));

// this method is called when the module is being created
        $this->setComponents(array(
                /*'errorHandler' => array(
                    'errorAction' => 'admin/login/error'),
                */
                'user' => array(
                    'class' => 'CWebUser',
                    'loginUrl' => YiiBase::app()->createUrl('/manager/login/login'),
                    'returnUrl'=> YiiBase::app()->createUrl('/manager/tree/'),
                )
            )
        );
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}

    /**
     * Return admin status.
     * @return boolean
     */
    public static function isAdmin() {
        if(Yii::app()->user->isGuest)
            return false;
        else {
            return true;
        }
    }
}
