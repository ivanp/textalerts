<?php

class Membership extends CActiveRecord
{
	public function primaryKey()
	{
		return array('company_id', 'user_id');
	}

	public function tableName()
	{
		return 'members';
	}

	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'company' => array(self::BELONGS_TO, 'Company', 'company_id')
		);
	}
}