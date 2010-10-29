<?php
/**
 * @TODO View user: display user's information, ownerships, subscriptions
 * @TODO Add/Edit user
 */

class UserController extends CAdminController
{
	public function actionIndex()
	{
		$users = User::model()->findAll();
		$this->render('index', array(
			'users'	=> $users
		));
	}

	public function actionAdd()
	{
		$user = new User();

		if (isset($_POST['User']))
		{
			$user->attributes=$_POST['User'];
			if ($user->save())
			{
				Yii::app()->user->setFlash('user',
							sprintf('User <a href="%s">%s</a> has been created',
									$this->createUrl('user/view', array('id'=>$user->id)),
									$user->email
				));
				$this->redirect(array('user/index'), true);
			}
		}

		$this->render('create', array(
			'model' => $user
		));
	}

	public function actionView($id)
	{
		echo '<h1>User View</h1>';
		// display user profile
		// display subscriptions
	}

	public function actionEdit($id)
	{
		$user = User::model()->findByPk($id);
		if (!($user instanceof User))
			throw new CHttpException (404, 'User not found');

		if (isset($_POST['User']))
		{
			$user->attributes=$_POST['User'];
			if ($user->save())
			{
				Yii::app()->user->setFlash('user',
							sprintf('User <a href="%s">%s</a> has been updated',
									$this->createUrl('user/view', array('id'=>$user->id)),
									$user->email
				));
				$this->redirect(array('user/index'), true);
			}
		}

		$this->render('edit', array(
			'user' => $user
		));
	}
}