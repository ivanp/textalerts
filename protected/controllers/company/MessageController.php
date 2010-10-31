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
	}

	public function actionCreate()
	{
		$user = Yii::app()->user->record;
		if (!Yii::app()->user->checkAccess('SuperAdministrator') && !$this->company->isAdministrator($user) && !$this->company->isSender($user))
			throw new CHttpException (401, 'Access Denied');

		$model = GroupMessage::factoryByCompany($this->company);
		$varname = get_class($model);

		if (isset($_POST[$varname]))
		{
			$model->attributes = $_POST[$varname];
			$model->status = 'pending';

			if ($model->save())
			{
				$queue = new MessageQueue;
				$queue->company_id = $this->company->id;
				$queue->message_id = $model->id;
				$queue->save();

				Yii::app()->user->setFlash('message', sprintf('New message to group "%s" has been created', $model->group->title));
				$this->redirect(array('message/index'), true);
			}
		}

		$groups = Group::modelByCompany($this->company)->findAll();
		$group_select = array();
		foreach ($groups as $group)
			$group_select[$group->id] = $group->title;
		$group_id = Yii::app()->getRequest()->getParam('group_id');
		$this->render('create', array(
			'model'	=> $model,
			'groups' => $group_select,
			'group_id' => $group_id
		));
	}

	public function actionIndex($status = 'all')
	{
		$messages = GroupMessage::modelByCompany($this->company)->findAll();

		$this->render('index', array(
			'messages' => $messages
		));
	}
}