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

		try
		{
			$company = new Company();
			$info = new CompanyInfo();

			$transaction = $company->dbConnection->beginTransaction();

			if (isset($_POST['Company'], $_POST['CompanyInfo']))
			{
				$company->attributes=$_POST['Company'];
				$info->attributes=$_POST['CompanyInfo'];
				$company_val = $company->validate();
				$info_val = $info->validate();
				if ($company_val && $info_val)
				{
					$company->save(false);
					$info->company_id = $company->id;
					$info->save(false);
					Yii::app()->user->setFlash('company',
								sprintf('Company <a href="%s">%s</a> has been created with owner <a href="%s">%s</a>',
										$this->createUrl('company/view', array('id'=>$company->id)),
										$company->name,
										$this->createUrl('user/view', array('id'=>$company->owner->id)),
										$company->owner->email
					));
					$this->redirect(array('company/index'), true);
					$transaction->commit();
				}
				else
					$transaction->rollBack();
			}
		}
		catch (CDbExceptiosn $e)
		{
			$transaction->rollBack();
			$company->addError('db', $e->getMessage());
		}

		$this->render('create', array(
			'company' => $company,
			'info' => $info
		));
	}

	public function actionEdit()
	{

	}
}