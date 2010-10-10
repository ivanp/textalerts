<?php

class MessageDestination extends CompanyActiveRecord
{
	public static function baseTableName()
	{
		return 'destination';
	}

	public static function modelByCompany(Company $company)
	{
		return parent::modelByCompany($company, __CLASS__);
	}

	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE  TABLE IF NOT EXISTS $tableName (
  `message_id` INT NOT NULL ,
  `group_id` INT NOT NULL ,
  INDEX `message` (`message_id` ASC) ,
  INDEX `group` (`group_id` ASC) )
ENGINE = MyISAM";
	}
}