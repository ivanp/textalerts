<?php

class CompanyController extends CCompanyController
{
	public function actionSettings()
	{
		$company = $this->company;
		$info = CompanyInfo::model()->findByPk($company->id);
		if (!($info instanceof CompanyInfo))
		{
			$info = new CompanyInfo();
			$info->company_id = $company->id;
		}

		if (isset($_POST['Company'], $_POST['CompanyInfo']))
		{
			$company->attributes = $_POST['Company'];
			$info->attributes = $_POST['CompanyInfo'];

			if ($company->save() && $info->save())
				Yii::app()->user->setFlash('message-settings', 'Company settings updated');
		}

		$this->render('settings', array(
			'company'	=> $company,
			'info' => $info
		));
	}
}