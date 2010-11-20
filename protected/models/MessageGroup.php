<?php

class MessageGroup extends CompanyActiveRecord
{
	public static function baseTableName()
	{
		return 'message_group';
	}

	public static function modelByCompany(Company $company)
	{
		return parent::modelByCompany($company, __CLASS__);
	}

	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE  TABLE IF NOT EXISTS $tableName (
  `message_id` BIGINT NOT NULL ,
  `group_id` INT NOT NULL ,
  INDEX `fk_message_group_message1` (`message_id` ASC) ,
  INDEX `fk_message_group_group1` (`group_id` ASC) )
ENGINE = MyISAM";
	}
}