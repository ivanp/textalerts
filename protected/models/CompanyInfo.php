<?php

class CompanyInfo extends CActiveRecord
{
	public function primaryKey()
	{
		return 'company_id';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

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
			array('email_pass,use_eztext,eztext_user,eztext_pass,bb_text', 'safe'),
			array('email_from', 'email'),
		);
	}

	public function beforeSave() {
		if (parent::beforeSave())
		{
			if ($this->use_eztext)
			{
				
				if (empty($this->eztext_user))
					$this->addError('eztext_user', 'You must provide username for EzText');
				if (empty($this->eztext_pass))
					$this->addError('eztext_pass', 'You must provide password for EzText');
				return !$this->hasErrors();
			}
			else
				return true;
		}
		else
			return false;
	}

	public function tableName()
	{
		return 'company_info';
	}
}