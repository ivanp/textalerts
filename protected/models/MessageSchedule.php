<?php

class MessageSchedule extends CompanyActiveRecord
{
	public static function baseTableName()
	{
		return 'message_schedule';
	}

	public static function modelByCompany(Company $company)
	{
		return parent::modelByCompany($company, __CLASS__);
	}

	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE TABLE IF NOT EXISTS $tableName (
  `message_id` BIGINT UNSIGNED NOT NULL ,
  `schedule_type` ENUM('once','repeat') NOT NULL ,
  `start_date` DATE NOT NULL ,
  `start_time` TIME NULL ,
  `repeat_type` ENUM('daily','weekly','monthly','yearly') NULL ,
  `repeat_every` INT UNSIGNED NULL ,
  `repeat_until` DATE NULL ,
  INDEX `fk_group_message_schedule_message1` (`message_id` ASC) ,
  PRIMARY KEY (`message_id`) ,
  INDEX `date` (`start_date` ASC) ,
  INDEX `time` (`start_time` ASC) )
ENGINE = MyISAM";
	}
}