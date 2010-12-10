<?php

class SiteController extends CCompanyController
{
//	public $layout='//layouts/column2';
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
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		//$that_user = User::model()->findByPk(2);

//		$user = current($this->company->administrators(array('condition' => 'user_id = 2')));
//		var_dump($user);
		
//		$company = Company::model()->findByPk(1);

		
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

	public function filters()
	{
		return array(
				'accessControl',
		);
	}

	public function accessRules()
	{
		return array(
				array('allow',
						'actions'=>array('dashboard', 'subscribe', 'unsubscribe'),
						'users'=>array('@'),
				),
				array('deny',
						'actions'=>array('dashboard', 'subscribe', 'unsubscribe'),
						'users'=>array('*'),
				),
		);
	}

	public function actionTest()
	{
		date_default_timezone_set('America/Indiana/Indianapolis');
		$dt = Zend_Date::now();

//$dt->setTimezone('America/Indiana/Indianapolis');

echo $dt;
		
	}
}