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

//		throw new Exception('yada yada');
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
				{
					if ($error['code']== 404)
						$this->render('error404', $error);
					else
	        	$this->render('error', $error);
				}
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
		var_dump(@date_default_timezone_set('ahem'));
//		$dt = Zend_Date::now();
//		$dt->setTimezone('EST');
//		var_dump($dt->get(Zend_Date::RFC_850));
////
//////$dt->setTimezone('America/Indiana/Indianapolis');
////
////echo $dt;
//		//var_dump(timezone_identifiers_list());
//		$this->render('test');
	}
}