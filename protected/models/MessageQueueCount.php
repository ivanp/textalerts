<?php

class MessageQueueCount extends CompanyActiveRecord
{
	public static function baseTableName()
	{
		return 'message_queue_count';
	}

	public static function modelByCompany(Company $company)
	{
		return parent::modelByCompany($company, __CLASS__);
	}
	
	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE TABLE IF NOT EXISTS $tableName (
  `message_queue_id` BIGINT UNSIGNED NOT NULL ,
  `total_text` INT NULL ,
  `total_mail` INT NULL ,
  `sent_text` INT NULL ,
  `sent_mail` INT NULL ,
  `failed_text` INT NULL ,
  `failed_mail` INT NULL ,
  INDEX `fk_message_queue_count_message_queue1` (`message_queue_id` ASC) ,
  PRIMARY KEY (`message_queue_id`) )
ENGINE = MyISAM";
	}
}