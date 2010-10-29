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
				array('allow',
						'roles'=>array('SuperAdministrator')
				),
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

		$model = new CreateNewMessageForm();

		$groups = Group::modelByCompany($this->company)->findAll();
		$group_id = Yii::app()->getRequest()->getParam('group_id');
		$this->render('create', array(
			'model'	=> $model,
			'groups' => $groups,
			'group_id' => $group_id
		));
	}

	public function actionIndex($status = 'all')
	{
		echo 'messages';
	}
}