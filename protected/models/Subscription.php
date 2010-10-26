<?php

class Subscription extends CompanyActiveRecord
{
	public function primaryKey()
	{
		return array('user_id', 'group_id');
	}

	public static function baseTableName()
	{
		return 'subscription';
	}

	public static function modelByCompany(Company $company)
	{
		return parent::modelByCompany($company, __CLASS__);
	}

	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE TABLE IF NOT EXISTS $tableName (
  `group_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `mail` TINYINT(1) NULL ,
  `text` TINYINT(1) NULL ,
  `since` DATETIME NULL ,
  INDEX `fk_group_users_group1` (`group_id` ASC) ,
  INDEX `fk_group_users_user1` (`user_id` ASC) ,
  INDEX `mail` (`mail` ASC) ,
  INDEX `text` (`text` ASC) 
  ) ENGINE = MyISAM";
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'group' => array(self::BELONGS_TO, 'Group', 'group_id'),
		);
	}
}
