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

	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE  TABLE IF NOT EXISTS $tableName (
  `user_id` INT NOT NULL ,
  `group_id` INT NOT NULL ,
  `mail` TINYINT(1) NULL ,
  `text` TINYINT(1) NULL ,
  `since` DATETIME NULL ,
  INDEX `user` (`user_id` ASC) ,
  INDEX `group` (`group_id` ASC) ,
  INDEX `subscription` (`user_id` ASC, `group_id` ASC))
ENGINE = MyISAM";
	}
}
