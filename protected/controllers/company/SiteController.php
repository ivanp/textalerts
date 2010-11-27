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

	public function actionDashboard()
	{
		$this->layout='//layouts/column2';
		$user = User::getLoggedUser();
		$this->render('dashboard', array(
			'user' => $user,
			'mail_alerts'	=> array(),
			'text_alerts' => array(),
			'avail_mail_alerts' => array(),
			'avail_text_alerts' => array()
//			'mail_alerts'	=> $user->mail_alerts,
//			'text_alerts' => $user->text_alerts,
//			'avail_mail_alerts' => Group::getAvailableMessageGroups(),
//			'avail_text_alerts' => Group::getAvailableTextGroups()
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

	public function actionTest()
	{
//		$msg=Message::modelByCompany($this->company)->findByAttributes(array('id'=>3));
//		var_dump(get_class($msg), get_class($msg->schedule));
//
//		$date=new Zend_Date();
//		//$date->setTimezone('PST');
//		$date->addYear(100);
//		print $date."<br/>";
//		$num=$date->get();
//		$int=(float)$num;
//		var_dump($num,$int);
		//$date->setTime()
		
//		print $date;
//		$dt = new Zend_Date(strtotime('09:59 pm'), Zend_Date::TIMESTAMP);
//		$now=Zend_Date::now();
//		$now->setMinute($dt);
		//$t=$dt->getHour()+1;
		//var_dump($t);
var_dump(date('Y-m-d H:i:s', 1290704400));
		//var_dump(date('Y-m-d H:i:s', strtotime('09:59 am')));
	}
}