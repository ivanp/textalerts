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
  `message_id` INT NOT NULL ,
  `time` DATETIME NULL ,
  `type` ENUM('info','error') NULL ,
  `body` TEXT NULL ,
  INDEX `fk_message_log_message1` (`message_id` ASC) ,
  INDEX `time` (`time` ASC) )
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
			'message' => array(self::BELONGS_TO, $this->getCompanyClass('GroupMessage'), 'message_id'),
		);
	}
}