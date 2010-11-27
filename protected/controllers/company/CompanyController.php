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
			$transaction=$company->getDbConnection()->beginTransaction();

			$oldimg=$info->img_logo;
			$company->attributes = $_POST['Company'];
			$info->attributes = $_POST['CompanyInfo'];
			$upload=CUploadedFile::getInstance($info,'img_logo');
			if ($upload instanceof CUploadedFile)
				$info->img_logo=sprintf('logo-%d.%s',time(),$upload->getExtensionName());

			if ($company->save() && $info->save())
			{
				if ($upload instanceof CUploadedFile)
				{
					$uploadDir=$this->company->getUploadDir();
					$oldimg=$uploadDir.'/'.$oldimg;
					if (file_exists($oldimg) && is_file($oldimg))
						unlink($oldimg);
					$file=$uploadDir.'/'.$info->img_logo;
					// Resample first
					$image=Yii::app()->image->load($upload->getTempName());
					$image->resize(240, 32, Image::HEIGHT);
					$image->save($file);
				}
				// Commit to database
				$transaction->commit();
				Yii::app()->user->setFlash('message-settings', 'Company settings updated');
				$this->redirect($this->createUrl('company/settings'),true);
			}
		}

		$this->render('settings', array(
			'company'	=> $company,
			'info' => $info
		));
	}
}