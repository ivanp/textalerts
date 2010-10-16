<?php

class Subscription extends CompanyActiveRecord
{
	public static function baseTableName()
	{
		return 'subscription';
	}

	public static function modelByCompany(Company $company)
	{
		return parent::modelByCompany($company, __CLASS__);
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::HAS_MANY, 'User', 'user_id'),
			'company' => array(self::HAS_MANY, 'Company', 'company_id'),
		);
	}
}
