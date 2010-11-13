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

	public function actionLogin()
	{
		if (!Yii::app()->user->isGuest)
			$this->redirect(array('site/index'));
		
		$model=new SuperAdminLoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['SuperAdminLoginForm']))
		{
			$model->attributes=$_POST['SuperAdminLoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(array('site/index'));
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}