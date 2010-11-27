<?php
/**
 * @TODO Send to multiple groups
 * @TODO Scheduled message
 */

class MessageController extends CCompanyController
{
	public function filters()
	{
		return array(
			'accessControl'
		);
	}

	public function accessRules()
	{
		return array(
				// Allow only Super Administrators
//				array('allow',
//						'roles'=>array('SuperAdministrator')
//				),
				array('allow',
						'users'=>array('@')
				),
				// Kick out all other users
				array('deny',
						'users'=>array('*'),
				),
		);
	}

	public function init()
	{
		parent::init();
		$user = Yii::app()->user->record;
		if (!Yii::app()->user->checkAccess('SuperAdministrator') && !$this->company->isAdministrator($user) && !$this->company->isSender($user))
			throw new CHttpException (401, 'Access Denied');
	}

	public function actionCreate()
	{
		$message=Message::factoryByCompany($this->company);
		$message->setScenario('create');
		$varname = get_class($message);

		if (isset($_POST[$varname]))
		{
			$message->attributes=$_POST[$varname];
			if (isset($_POST['cmd_start']))
				$message->status='pending';
			else
				$message->status='draft';

			if ($message->save()) 
			{
				Yii::app()->user->setFlash('flash-success', 'Message has been created');
				$this->redirect($this->createUrl('/message/index'), true);
			}
		}
		else
		{
			$message->type='now';
			$message->start=time();
			$message->repeatUntil=time();
		}

		$crit=Group::modelByCompany($this->company)->getDbCriteria();
		$crit->order='title';
		$groups = Group::modelByCompany($this->company)->findAll($crit);
		$group_select = array();
		foreach ($groups as $group)
			$group_select[$group->id] = $group->title;
		$group_id = Yii::app()->getRequest()->getParam('group_id');
		$this->render('create', array(
			'message'	=> $message,
			'groups' => $group_select,
			'group_id' => $group_id
		));
	}

	public function actionIndex($status = 'all')
	{
		$messages = Message::modelByCompany($this->company)->findAll();

		$this->render('index', array(
			'messages' => $messages
		));
	}
}