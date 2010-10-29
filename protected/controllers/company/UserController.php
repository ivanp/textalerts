<?php

Yii::import('application.models.forms.*');

class UserController extends CCompanyController
{
	public function actionIndex()
	{
		
	}

	public function actionView($id)
	{
		$user = User::model()->findByPk($id);
		$model = new CompanyMemberInfoForm();
		$this->render('view', array(
			'company' => $this->company,
			'user' => $user,
			'model' => $model
		));
	}

	public function actionEdit($id)
	{
		$user = User::model()->findByPk($id);
		$model = new CompanyMemberInfoForm();
		$this->render('edit', array(
			'company' => $this->company,
			'user' => $user,
			'model' => $model
		));
	}
}