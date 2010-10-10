<?php

class GroupMessage extends CompanyActiveRecord
{
	public static function baseTableName()
	{
		return 'message';
	}

	public static function modelByCompany(Company $company)
	{
		return parent::modelByCompany($company, __CLASS__);
	}

	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE  TABLE IF NOT EXISTS $tableName (
  `id` INT NOT NULL ,
  `created` DATETIME NULL ,
  `body` TEXT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = MyISAM";
	}
}