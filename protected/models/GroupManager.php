<?php

class GroupManager extends CompanyActiveRecord
{
	public static function baseTableName()
	{
		return 'group_managers';
	}

	public static function modelByCompany(Company $company)
	{
		return parent::modelByCompany($company, __CLASS__);
	}

	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE  TABLE IF NOT EXISTS $tableName (
  `group_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `is_admin` TINYINT(1) NULL ,
  `is_sender` TINYINT(1) NULL ,
  INDEX `group` (`group_id` ASC) ,
  INDEX `user` (`user_id` ASC) )
ENGINE = MyISAM";
	}
}