<?php

class MessageQueue extends CompanyActiveRecord
{
	public static function baseTableName()
	{
		return 'message_queue';
	}

	public static function modelByCompany(Company $company)
	{
		return parent::modelByCompany($company, __CLASS__);
	}

	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE TABLE IF NOT EXISTS $tableName (
  `id` BIGINT NOT NULL AUTO_INCREMENT ,
  `message_id` BIGINT UNSIGNED NOT NULL ,
  `schedule_on` DATETIME NOT NULL ,
  `status` ENUM('pending','progress','sent') NOT NULL ,
  `last_retry` DATETIME NULL ,
  `sent_on` DATETIME NULL ,
  INDEX `fk_group_message_occur_message1` (`message_id` ASC) ,
  PRIMARY KEY (`id`) ,
  INDEX `schedule` (`schedule_on` ASC) ,
  INDEX `status` (`status` ASC) )
ENGINE = MyISAM";
	}
}