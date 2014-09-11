<?php
class SiteController extends Controller
{

    //public $layout='//layouts/column_modx';


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

        $id = (int) Yii::app()->request->getParam('id');


        if(!isset($id)){
            throw new CHttpException(404,'The requested page does not exist.');
        }

        $criteria = new EMongoCriteria(array(
            'condition' => array('id'=>$_GET['id']),
        ));

        $model = Content::model()->findOne($criteria);

        if($model==null){
            throw new CHttpException(404,'The requested page does not exist.');
        }

        $parser = new Parser($model->tpl->content,$model);
        $parser->run();


        $this->render('index',array('content'=>$parser->html));
	}

    /*
     * импортируем данные из mysql В MongoDB
     */
    public function actionImport(){
        $import = new ImportFromMysql();
        $import->import();
        $this->render('index');
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
			else
				$this->render('error', $error);
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