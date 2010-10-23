<?php

class CompanyController extends CCompanyController
{
	public function actionSettings()
	{
		$model = new CompanySettingsForm;
		$this->render('settings', array(
			'company'	=> $this->company,
			'model'=>$model
		));
	}
}