<?php

class CompanyManager extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'company_manager';
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'company' => array(self::HAS_MANY, 'Company', 'company_id'),
			'user' => array(self::HAS_MANY, 'User', 'user_id'),
		);
	}
}