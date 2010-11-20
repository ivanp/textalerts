<?php

class MessageLog extends CompanyActiveRecord
{
	public static function baseTableName()
	{
		return 'message_log';
	}

	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE TABLE IF NOT EXISTS $tableName (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `message_id` BIGINT UNSIGNED NOT NULL ,
  `time` INT NULL ,
  `type` ENUM('info','error') NULL ,
  `body` TEXT NULL ,
  INDEX `time` (`time` ASC) ,
  INDEX `fk_message_log_message1` (`message_id` ASC) ,
  PRIMARY KEY (`id`) )
ENGINE = MyISAM";
	}

	public static function modelByCompany(Company $company)
	{
		return parent::modelByCompany($company, __CLASS__);
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'message' => array(self::BELONGS_TO, $this->getCompanyClass('Message'), 'message_id'),
		);
	}
}