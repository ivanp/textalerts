<?php

class CompanyInfo extends CActiveRecord
{
	public function relations()
	{
		return array(
			'company'	=> array(self::BELONGS_TO, 'Company', 'company_id')
		);
	}

	public function rules()
	{
		return array(
			array('heading,title,email_from,email_server', 'required'),
			array('email_from', 'email'),
		);
	}
}