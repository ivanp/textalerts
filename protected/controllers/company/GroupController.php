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
				array('allow',
						'users'=>array('@')
				),
				array('deny',
						'users'=>array('*')
				)
		);
	}

	public function actionIndex()
	{
		$criteria=new CDbCriteria();
		$total_groups=Group::modelByCompany($this->company)->count($criteria);
		$pages=new CPagination($total_groups);
		$pages->pageSize=10;
    $pages->applyLimit($criteria);
		$groups=Group::modelByCompany($this->company)->findAll($criteria);

		$this->render('index', array(
			'groups'=>$groups,
			'pages'=>$pages
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
		if (!Yii::app()->user->checkAccess('SuperAdministrator') && !$this->company->isAdministrator(Yii::app()->user->record))
			throw new CHttpException(401,'Unauthorized access');

		$group=Group::modelByCompany($this->company)->findByPk($id);
		if (!($group instanceof Group))
			throw new CHttpException (404,'Group not found');
		$varname=get_class($group);
		if (isset($_POST[$varname]))
		{
			$group->attributes=$_POST[$varname];
			if ($group->save())
			{
				Yii::app()->user->setFlash('group-view','Group info has been updated');
				$this->redirect($this->createUrl('group/view',array('id'=>$group->id)),true);
			}
		}

		$this->render('edit', array(
			'group'=>$group
		));
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
		if (($user_id != $logged_user->id) && (!$this->company->isAdministrator($logged_user)))
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
		if (($user_id != $logged_user->id) && (!$this->company->isAdministrator($logged_user)))
			throw new CHttpException(401, 'Unauthorized access', 401);

		$group->unsubscribeUser($user, $type);
	}

	public function actionSubscription()
	{
		$request=Yii::app()->getRequest();
		if (!$request->getIsPostRequest())
			throw new CHttpException (405, 'Only POST allowed');
		$type=$request->getPost('type');
		if ($type !== 'mail' && $type !== 'text')
			throw new CHttpException(404, 'Invalid type');
		$group_id=$request->getPost('group_id');
		$group=Group::modelByCompany($this->company)->findByPk($group_id);
		if (!($group instanceof Group))
			throw new CHttpException(404, 'Unknown group');
		$user_id=$request->getPost('user_id');
		$user = User::modelByCompany($this->company)->findByPk($user_id);
		if (!($user instanceof User))
			throw new CHttpException(404, 'Unknown user');
		// Check access
		$logged_user = Yii::app()->user->record;
		if (($user_id != $logged_user->id) && (!$this->company->isAdministrator($logged_user)))
			throw new CHttpException(401, 'Unauthorized access', 401);
		$command=$request->getPost('command');
		switch ($command)
		{
			case 'subscribe':
				$group->subscribeUser($user, $type);
				break;
			case 'unsubscribe':
				$group->unsubscribeUser($user, $type);
				break;
			default:
				throw new CHttpException(404, 'Invalid mode');
		}
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

	public function actionManager()
	{
		$model=new GroupManagerForm();
		$this->render('manager',array(
			
		));
	}

	public function actionAddemail($id)
	{
		if (!Yii::app()->getRequest()->getIsPostRequest())
			throw new CHttpException(405,'Only POST allowed');
		$email=Yii::app()->getRequest()->getPost('mail');
		$group=Group::modelByCompany($this->company)->findByPk($id);
		if (!($group instanceof Group))
			throw new CHttpException(404,'Group not found');
		$user=User::modelByCompany($this->company)->find('email=:email',
			array(':email'=>$email));
		if ($user instanceof User) // email already in database
		{
			//$subscr=Subscription::modelByCompany($this->company)->find('user_id=:user_id',array(':user_id'=>$user->id));
			if ($group->isUserSubscribed($user))
			{
				echo CJSON::encode(array(
					'status'=>'error',
					'message'=>sprintf('Email %s already subscribed to this group',$user->email)
				));
				Yii::app()->end();
			}
		}
		else
		{
			$user=User::factoryByCompany($this->company);
			$user->email=$email;
			if (!$user->save())
			{
				echo CJSON::encode(array(
					'status'=>'error',
					'message'=>join(',',$user->getErrors())
				));
				Yii::app()->end();
			}
		}

		$group->subscribeUser($user, 'mail');

		echo CJSON::encode(array(
			'status'=>'success',
			'message'=>sprintf('Email %s is now subscribed to this group',$user->email),
			'row'=>$this->renderPartial('_user_row', array('group'=>$group,'user'=>$user), true),
			'user_id'=>$user->id,
			'group_id'=>$group->id
		));
	}
}
