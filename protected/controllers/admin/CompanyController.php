<?php

class CompanyController extends CAdminController
{
	public function actionIndex()
	{
		$companies = Company::model()->findAll();
		$this->render('index', array(
			'companies' => $companies
		));
	}
	
	public function actionCreate()
	{
		$company = new Company();

		if (isset($_POST['Company']))
		{
			$company->attributes=$_POST['Company'];
			if ($company->save())
			{
				Yii::app()->user->setFlash('company', 
							sprintf('Company <a href="%s">%s</a> has been created with owner <a href="%s">%s</a>',
									$this->createUrl('company/view', array('id'=>$company->id)),
									$company->name,
									$this->createUrl('user/view', array('id'=>$company->owner->id)),
									$company->owner->email
				));
				$this->redirect(array('company/index'), true);
			}
		}

		$this->render('create', array(
			'model' => $company
		));
	}

	public function actionEdit()
	{

	}
}