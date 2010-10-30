<?php

class CompanyController extends CCompanyController
{
	public function actionSettings()
	{
		$company = $this->company;
		$info = $company->info;
		if (!($info instanceof CompanyInfo))
		{
			$info = new CompanyInfo();
		}

		if (isset($_POST['Company'], $_POST['CompanyInfo']))
		{
			$company->attributes = $_POST['Company'];
			$info->attributes = $_POST['CompanyInfo'];

			if ($company->validate() && $info->validate())
			{
				$company->save(false);
				$info->company_id = $company->id;
				$info->save(false);

			}
		}

		$this->render('settings', array(
			'company_base'	=> $company,
			'company_info' => $info
		));
	}
}