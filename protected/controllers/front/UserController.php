<?php

Yii::import('application.vendors.*');
require_once('Zend/Mail.php');

class UserController extends Controller {
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionRegister() {
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			$password = $model->generatePassword();
			if($model->save()) {
				$this->render('register_complete',array(
					'model'=>$model,
					'password'=>$password
				));

				$content = $this->renderPartial('register_email',array(
					'model'=>$model,
					'password'=>$password,
					'loginUrl' => $this->createAbsoluteUrl('user/login'),
					'resetPwdUrl' => $this->createAbsoluteUrl('user/forgot_password')
				), true);

				// Send e-mail
				$mail = new Zend_Mail();
				$mail->setBodyHtml($content);
				$mail->setFrom(Yii::app()->params['adminEmail'], Yii::app()->name);
				$mail->addTo($model->email, $model->first_name.' '.$model->last_name);
				$mail->setSubject('TestSubject');
				$mail->send();

				Yii::app()->end();
			}
		}

		$this->render('register',array(
			'model'=>$model,
			'signupUrl' => $this->createUrl('user/register')
		));
	}

	public function actionDashboard()
	{

	}

	public function actionForgotPassword()
	{
		
	}

	public function actionPassword()
	{
		$this->render('password');
	}

}