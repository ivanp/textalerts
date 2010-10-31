<?php

class StatusUpdate extends CompanyActiveRecord
{
	public static function baseTableName()
	{
		return 'status_update';
	}

	public static function modelByCompany(Company $company)
	{
		return parent::modelByCompany($company, __CLASS__);
	}

	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE TABLE IF NOT EXISTS $tableName (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `time` DATETIME NULL ,
  `message` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_status_update_user1` (`user_id` ASC) ,
  INDEX `time` (`time` DESC) )
ENGINE = MyISAM";
	}
}