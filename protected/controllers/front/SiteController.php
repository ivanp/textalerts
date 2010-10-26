<?php

class SiteController extends CFrontController
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
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	public function actionDashboard()
	{
		$this->layout='//layouts/column2';
		$this->render('dashboard', array(
			'user' => Yii::app()->user->record
		));
	}

	public function actionSubscribe($type, $group_id)
	{
		switch ($type) {
			case 'mail':
				$table = 'message_groups_subscribers';
				break;
			case 'text':
				$table = 'text_group_subscribers';
				break;
			default:
				throw new CHttpException(400, 'Incorrect type given.');
		}
		$group = Group::model()->findByPk($group_id);
		if ($group===null) {
			throw new CHttpException(404, 'Group #'.$group_id.' does not exist in database.');
		}

		$user = User::getLoggedUser();
		$user_id = $user->subscriber_id;
		$conn = Yii::app()->db;
		$cmd = $conn->createCommand('select * from '.$table.' where message_group_id = :group_id and subscriber_id = :user_id');
		$cmd->bindParam(':group_id', $group_id, PDO::PARAM_INT);
		$cmd->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		$row = $cmd->queryRow();
		
		if (!$row) {
			$cmd = $conn->createCommand('insert into '.$table.' (message_group_id, subscriber_id) values (:group_id, :user_id)');
			$cmd->bindParam(':group_id', $group_id, PDO::PARAM_INT);
			$cmd->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			$cmd->execute();

			$group = Group::model()->findByPk($group_id);
			echo $this->renderPartial('_group_row',array(
					'group'=>$group,
					'type' =>$type
				), true);
			echo $this->renderPartial('_add_group',array(
					'group'=>$group,
					'type' =>$type
				), true);
			Yii::app()->end();
		}
	}

	public function actionUnsubscribe($type, $group_id) {
		switch ($type) {
			case 'mail':
				$table = 'message_groups_subscribers';
				break;
			case 'text':
				$table = 'text_group_subscribers';
				break;
			default:
				throw new CHttpException(400, 'Incorrect type given.');
		}

		$user = User::getLoggedUser();
		$user_id = $user->subscriber_id;
		$conn = Yii::app()->db;

		$cmd = $conn->createCommand('select * from '.$table.' where message_group_id = :group_id and subscriber_id = :user_id');
		$cmd->bindParam(':group_id', $group_id, PDO::PARAM_INT);
		$cmd->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		$row = $cmd->queryRow();

		if ($row) {
			$cmd = $conn->createCommand('delete from '.$table.' where message_group_id = :group_id and subscriber_id = :user_id');
			$cmd->bindParam(':group_id', $group_id, PDO::PARAM_INT);
			$cmd->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			$rows = $cmd->execute();

			$group = Group::model()->findByPk($group_id);
			echo $this->renderPartial('_add_group',array(
					'group'=>$group,
					'type' =>$type
				), true);
			Yii::app()->end();
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

}