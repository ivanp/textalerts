<?php

class GroupController extends CCompanyController
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
				array('deny',
						'actions'=>array('subscribe','unsubscribe','create','edit'),
						'users'=>array('?')
				),
				array('allow',
						'actions'=>array('subscribe','unsubscribe','create','edit'),
						'users'=>array('@')
				),
				array('allow',
						'users'=>array('*')
				)
		);
	}

	public function actionIndex()
	{
		$groups = Group::modelByCompany($this->company)->findAll();
		$this->render('index', array(
			'groups'=>$groups
		));
	}

	public function actionView($id)
	{
		$group = Group::modelByCompany($this->company)->findByPk($id);
		if ($group instanceof Group)
		{
			$this->render('view', array(
				'company'=>$this->company,
				'group'=>$group
			));
		}
		else
		{
			throw new CHttpException(404, 'Group not found', 404);
		}
	}

	public function actionEdit($id)
	{

	}

	public function actionDelete($id)
	{
		
	}

	/**
	 *
	 * @param int $group_id
	 * @param int $user_id
	 * @param string $type mail or text
	 */
	public function actionSubscribe($group_id, $user_id, $type)
	{
		if (!Yii::app()->getRequest()->getIsPostRequest())
			throw new CHttpException (405, 'Only POST allowed');
		// Check parameters
		if ($type !== 'mail' && $type !== 'text')
			throw new CHttpException(404, 'Invalid type');
		$group = Group::modelByCompany($this->company)->findByPk($group_id);
		if (!($group instanceof Group))
			throw new CHttpException(404, 'Unknown group');
		$user = User::modelByCompany($this->company)->findByPk($user_id);
		if (!($user instanceof User))
			throw new CHttpException(404, 'Unknown user');
		// Check access
		$logged_user = Yii::app()->user->record;
		if (($user_id != $logged_user->id) && ($company->isAdministrator($logged_user)))
			throw new CHttpException(401, 'Unauthorized access', 401);

		$group->subscribeUser($user, $type);
	}

	public function actionUnsubscribe($group_id, $user_id, $type)
	{
		if (!Yii::app()->getRequest()->getIsPostRequest())
			throw new CHttpException (405, 'Only POST allowed');
		if ($type !== 'mail' && $type !== 'text')
			throw new CHttpException(404, 'Invalid type');
		$group = Group::modelByCompany($this->company)->findByPk($group_id);
		if (!($group instanceof Group))
			throw new CHttpException(404, 'Unknown group');
		$user = User::modelByCompany($this->company)->findByPk($user_id);
		if (!($user instanceof User))
			throw new CHttpException(404, 'Unknown user');
		// Check access
		$logged_user = Yii::app()->user->record;
		if (($user_id != $logged_user->id) && ($company->isAdministrator($logged_user)))
			throw new CHttpException(401, 'Unauthorized access', 401);

		$group->unsubscribeUser($user, $type);
	}

	public function actionCreate()
	{
		if (!Yii::app()->user->checkAccess('SuperAdministrator') && !$this->company->isAdministrator(Yii::app()->user->record))
			throw new CHttpException(401, 'Unauthorized access');
		$group = Group::factoryByCompany($this->company);
		$varname = get_class($group);

		if (isset($_POST[$varname]))
		{
			$group->attributes=$_POST[$varname];
			if ($group->save())
			{
				Yii::app()->user->setFlash('group', sprintf('Group "%s" has been created', $group->title));
				$this->redirect(array('group/index'), true);
			}
		}

		$this->render('create', array(
			'model' => $group
		));
	}
}