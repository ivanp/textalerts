<?php

Yii::import('application.models.forms.*');
require_once('Zend/Mail.php');

class UserController extends CCompanyController
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
						'actions'=>array('profile','subscription'),
						'users'=>array('@')
				),
				array('deny',
					'actions'=>array('profile'),
					'users'=>array('?')),
		);
	}
	
	public function actionIndex()
	{
		$criteria=new CDbCriteria();
		$criteria->order='first_name, last_name, id';
		$total_users=User::modelByCompany($this->company)->count($criteria);
		$pages=new CPagination($total_users);
		$pages->pageSize=20;
    $pages->applyLimit($criteria);
		$users=User::modelByCompany($this->company)->findAll($criteria);

		$this->render('index', array(
			'users'=>$users,
			'pages'=>$pages
		));
	}

	public function actionView($id)
	{
		$user = User::modelByCompany($this->company)->findByPk($id);
		$model = new CompanyMemberInfoForm();
		$this->render('view', array(
			'company' => $this->company,
			'user' => $user,
			'model' => $model
		));
	}

	public function actionEdit($id)
	{
		$user = User::modelByCompany($this->company)->findByPk($id);
		$model = new CompanyMemberInfoForm();
		$this->render('edit', array(
			'company' => $this->company,
			'user' => $user,
			'model' => $model
		));
	}

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

		// clear the password field
		$model->password='';
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionRegister() 
	{
		$model = User::factoryByCompany($this->company);
		$model->setScenario('register');
		$varname = get_class($model);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST[$varname]))
		{
			$model->attributes=$_POST[$varname];
			$password = $model->generatePassword();
			if ($model->save())
			{
				$this->render('register_complete',array(
					'model'=>$model
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
				$mail->setFrom($this->company->info->email_from);
				$mail->addTo($model->email, $model->first_name.' '.$model->last_name);
				$mail->setSubject('Registration on '.HOSTNAME);
				$mail->send();

				Yii::app()->end();
			}
		}

		$this->render('register',array(
			'model'=>$model,
			'signupUrl' => $this->createUrl('user/register')
		));
	}

	public function actionForgotPassword()
	{
		$model=new ForgotPasswordForm();

		if (isset($_POST['ForgotPasswordForm']))
		{
			$model->attributes=$_POST['ForgotPasswordForm'];
			if ($model->validate())
			{
				$user=User::modelByCompany($this->company)->find('email=:email',array(':email'=>$model->email));
				if (!($user instanceof User))
					throw new CHttpException(404, 'Invalid email given');
				$user->generateCode();
				$user->setScenario('recoverpassword');
				$user->save();

				$content = $this->renderPartial('forgotpassword_email',array(
					'resetPwdUrl' => $this->createAbsoluteUrl('user/resetpassword',array('email'=>$user->email,'code'=>$user->code))
				), true);

				// Send e-mail
				$mail = new Zend_Mail();
				$mail->setBodyHtml($content);
				$mail->setFrom($this->company->info->email_from);
				$mail->addTo($user->email, $user->getDisplayName());
				$mail->setSubject('Password recovery on homeduck.com');
				$mail->send();

				$this->render('forgotpassword_complete');
				Yii::app()->end();
			}
		}

		$this->render('forgotpassword', array(
			'model'=>$model
		));
	}

	public function actionResetpassword()
	{
		if (!Yii::app()->user->isGuest)
			$this->redirect('/', true);

		$email=Yii::app()->getRequest()->getParam('email');
		$code=Yii::app()->getRequest()->getParam('code');

		if ($email===null || $code===null)
			throw new CHttpException(400, 'Required parameter not exist');
		$user=User::modelByCompany($this->company)->find('email=:email',array(':email'=>$email));
		if (!($user instanceof User)) // such email not found
			throw new CHttpException (404, 'E-mail address not found');
		if (strlen($code) && 0===strcmp($code,$user->code))
		{
			$model=new ResetPasswordForm();
			if (isset($_POST['ResetPasswordForm']))
			{
				$model->attributes=$_POST['ResetPasswordForm'];
				if ($model->validate())
				{
					$user->password=$model->password;
					$user->password_repeat=$model->password_repeat;
					$user->code=null;
					if ($user->save())
					{
						Yii::app()->user->setFlash('user-login','You have reset your password. You can now login with your new password.');
						$this->redirect(array('user/login'), true);
					}
				}
			}

			$this->render('resetpassword',array(
				'model'=>$model,
				'user'=>$user
			));
		}
		else
		{
			$this->render('resetpassword_invalidcode',array(
				'forgotPwdUrl'=>$this->createCompanyUrl($this->company, 'user/forgotpassword')
			));
		}
	}

	public function actionProfile($id = null)
	{
		$own_profile = true;
		$set_privilege = false;
		$user = User::getLoggedUser();
		if ($id !== null)
		{
			$own_profile = false;
			if (!$this->company->isAdministrator($user))
				throw new CHttpException(401, 'Access Denied');
			else
			{
				$owner = $this->company->owner;
				// Editing other user's profile
				if ($user->id!=$id)
				{
					// Administrator cannot edit owner's profile
					if ($id==$owner->id)
						throw new CHttpException(401, 'Access Denied');
					$set_privilege=true;
				}
			}
			$user = User::modelByCompany($this->company)->findByPk($id);
			if (!($user instanceof User))
				throw new CHttpException(404, 'User not found');
		}
		$phone = $user->phone;
		if (!($phone instanceof PhoneNumber))
		{
			$phone = PhoneNumber::factoryByCompany($this->company);
			$phone->user_id = $user->id;
			$phone->save();
		}

		$vn_user = get_class($user);
		$vn_phone = get_class($phone);

		if (isset($_POST[$vn_user], $_POST[$vn_phone]))
		{
			$user->attributes = $_POST[$vn_user];
			$phone->attributes = $_POST[$vn_phone];
			if ($set_privilege && isset($_POST[$vn_user]['level']))
				$user->level = $_POST[$vn_user]['level'];
			if ($user->save() && $phone->save())
				Yii::app()->user->setFlash('user-profile', 'User profile has been updated');
		}

		unset($user->password);
		$user->password_repeat = '';
		$carriers = array();
		foreach (Carrier::model()->findAll() as $carrier)
			$carriers[$carrier->id] = $carrier->name;
		if ($phone->number=='')
			$phone->carrier_id='';

		$this->render('profile', array(
			'user' => $user,
			'phone' => $phone,
			'carriers' => $carriers,
			'own_profile' => $own_profile,
			'set_privilege'=>$set_privilege
		));
	}

	public function actionPassword()
	{
		$this->render('password');
	}

	public function actionConfirmphone()
	{
		if (!Yii::app()->getRequest()->getIsPostRequest())
			throw new CHttpException (405, 'Only POST allowed');
		$user = User::getLoggedUser();
		if (!$user->havePhoneNumber())
			$this->jsonResponse(false,'No phone number');
		if ($user->isPhoneConfirmed())
			$this->jsonResponse(true, 'Phone number already confirmed');

		$code = Yii::app()->getRequest()->getPost('code');
		$phone = $user->phone;
		if ($phone->code == $code)
		{
			$phone->confirmed = 1;
			$phone->save();
			$this->jsonResponse(true, 'Phone number confirmed!');
		}
		else
			$this->jsonResponse(false, 'Invalid confirmation code');
	}

	public function actionResend()
	{
		
	}

	public function actionSearchac()
	{
		$term=Yii::app()->getRequest()->getParam('term');

		$users=User::modelByCompany($this->company)->limit(5)->findAll('email LIKE :email',array(':email'=>$term.'%'));
		$arr=array();
		foreach ($users as $user)
		{
			$arr[]=array(
				'label'=>$user->email,
			);
		}
		echo CJSON::encode($arr);
	}

	public function actionSubscription($id=null)
	{
		$editing_own=true;
		$user=User::getLoggedUser();
		if ($id !== null)
		{
			$own_profile=false;
			if (!$this->company->isAdministrator($user))
				throw new CHttpException(401, 'Access Denied');
			else
			{
				$owner=$this->company->owner;
				// Editing other user's profile
				if ($user->id!=$id)
				{
					if ($id==$owner->id)
						throw new CHttpException(401, 'Access Denied');
				}
			}
			$user = User::modelByCompany($this->company)->findByPk($id);
			if (!($user instanceof User))
				throw new CHttpException(404, 'User not found');
		}

		$request=Yii::app()->getRequest();
		if ($request->getIsPostRequest())
		{
			$group=Group::modelByCompany($this->company)
							->findByPk($request->getPost('group_id'));
			if (!($group instanceof Group))
				throw new CHttpException(404);
			
			if ($group->isUserSubscribed($user))
			{
				if ($request->getIsAjaxRequest())
				{
					echo CJSON::encode(array(
						'status'=>'error',
						'message'=>'User already subscribed'
					));
					Yii::app()->end();
				}
				else
					Yii::app()->user->setFlash('user-subscription',
									sprintf('Email %s has already subscribed to group "%s"',
													$user->email,$group->title));
			}
			else
			{
				$group->subscribeUser($user, 'mail');
				if ($request->getIsAjaxRequest())
				{
					echo CJSON::encode(array(
						'status'=>'success',
						'row'=>$this->renderPartial('_subscription_row', array(
								'group'=>$group,
								'user'=>$user), true),
						'user_id'=>$user->id,
						'group_id'=>$group->id
					));
					Yii::app()->end();
				}
				else
					Yii::app()->user->setFlash('user-subscription',
									sprintf('Email %s has been added to group "%s"',
													$user->email,$group->title));
			}
		}

		$available_groups=array();
		$subscribed_groups=array();
		$groups=Group::modelByCompany($this->company)
				->sortByTitle()->findAll();
		foreach ($groups as $group)
		{
			if ($user->subscriptions(array('condition'=>'group_id='.$group->id)))
				$subscribed_groups[]=$group;
			else
				$available_groups[]=$group;
		}

		$this->render('subscription',array(
			'user'=>$user,
			'subscribed_groups'=>$subscribed_groups,
			'available_groups'=>$available_groups
		));
	}

	private function jsonResponse($success=true,$message)
	{
		echo json_encode(array('status'=>$success ? 1 : 0,'message'=>$message));
		exit;
	}
}