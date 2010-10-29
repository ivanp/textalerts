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
  `id` INT NOT NULL AUTO_INCREMENT ,
  `group_id` INT NOT NULL ,
  `created` DATETIME NULL ,
  `body` VARCHAR(160) NULL ,
  `status` ENUM('draft','pending','sending','sent') NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_message_group1` (`group_id` ASC) ,
  INDEX `created` (`created` ASC) ,
)
ENGINE = MyISAM";
	}

	public function rules()
	{
		return array(
			array('body', 'required'),
		);
	}

	public function relations()
	{
		$group_model = Group::modelByCompany($this->company);
		$log_model = MessageLog::modelByCompany($this->company);
		//$log_model =
		return array(
			'group' => array(self::BELONGS_TO, get_class($group_model), 'group_id'),
			'logs' => array(self::HAS_MANY, get_class($log_model), 'message_id')
		);
	}
}