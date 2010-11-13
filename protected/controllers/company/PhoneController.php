<?php

class PhoneController extends CCompanyController
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
					'users'=>array('?')),
		);
	}

	public function actionConfirm()
	{
		$user = User::getLoggedUser();
		if ($user->isPhoneConfirmed())
		{
			Yii::app()->user->setFlash('user-profile', 'Your phone number is already confirmed.');
			$this->redirect('user/profile', true);
		}
		elseif (!$user->havePhoneNumber())
			$this->redirect('user/profile', true);

		$phone = $user->phone;

		$model = new ConfirmPhoneForm();

		if (isset($_POST['ConfirmPhoneForm']))
		{
			$model->attributes = $_POST['ConfirmPhoneForm'];
			if ($model->validate())
			{
				$phone->confirmed = 1;
				$phone->code = null;
				$phone->save();
				Yii::app()->user->setFlash('user-profile', 'Your phone number have been successfully confirmed');
				$this->redirect(array('user/profile'));
			}
		}

		$this->render('confirm', array(
			'model' => $model,
			'user' => $user,
			'phone' => $phone
		));
	}

	public function actionResend()
	{
		$user = User::getLoggedUser();
		if (!$user->havePhoneNumber())
			$this->redirect('user/profile');
		if ($user->isPhoneConfirmed())
		{
			Yii::app()->user->setFlash('phone-confirm', 'Confirmation code has been resent');
			$this->redirect('user/profile', true);
		}

		$phone = $user->phone;
		$phone->generateCode();
		$phone->save();
		$phone->sendConfirmationCode();

		Yii::app()->user->setFlash('phone-confirm', 'Confirmation code has been resent');
		$this->redirect(array('phone/confirm'));
	}
}