<?php
class SiteController extends Controller
{

    public $layout='//layouts/column_admin';


    /*
    public function filters()
    {
        return array(
            array(
                'COutputCache',
                'duration'=>100,
                'varyByParam'=>array('alias'),
            ),
        );
    }*/
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{

        $id = Yii::app()->request->getParam('id');

        if(empty($id)){
            throw new CHttpException(404,'The requested page does not exist.');
        }

        $model = Content::model()->findOne(array('_id'=>$id));

        $parser = new Parser(
            $model->tpl->content
            ,
            $model
        );
        $parser->run();


        $this->render('index',array('content'=>$parser->html));
	}

    /*
     * импортируем данные из mysql В MongoDB
     */
    public function actionImport(){
        $import = new ImportFromMysql();
        $import->import();
        die('Import complete');
        //$this->render('index');
    }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else{
                ///из настроек системы находим документ, который будет выводится в 404 ошибке
                $model = Content::model()->findOne(array('_id'=>(int)Yii::app()->config->get('SYSTEM.NOT_FIND_PAGE')));

                $parser = new Parser($model->tpl->content,$model);
                $parser->run();

                $this->render('index',array('content'=>$parser->html));
            }

				//$this->render('error', $error);
		}
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}