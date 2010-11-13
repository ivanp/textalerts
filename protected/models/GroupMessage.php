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
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL COMMENT 'Message creator' ,
  `body` VARCHAR(160) NOT NULL ,
  `type` ENUM('now','schedule') NOT NULL ,
  `created_on` DATETIME NOT NULL ,
  `updated_on` DATETIME NULL ,
  `status` ENUM('draft','pending','progress','sent') NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_message_user1` (`user_id` ASC) ,
  INDEX `type` (`type` ASC) ,
  INDEX `status` (`status` ASC) )
ENGINE = MyISAM";
	}

	public function rules()
	{
		return array(
//			array('body,group_id', 'required'),
		);
	}

	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if ($this->getIsNewRecord())
				$this->created_on=date('Y-m-d H:i:s');
			else
				$this->updated_on=date('Y-m-d H:i:s');

			return true;
		}
		else
			return false;
	}

	public function attributeLabels()
	{
		return array(
			'body' => 'Message'
		);
	}

	public function relations()
	{
		$groupModel=Group::modelByCompany($this->company);
		$userModel=User::modelByCompany($this->company);
		return array(
			'recipients'=>array(self::MANY_MANY,get_class($groupModel),$groupModel->tableName().'(message_id,group_id)'),
			'logs'=>array(self::HAS_MANY,$this->getCompanyClass('MessageLog'),'message_id'),
			'schedule'=>array(self::HAS_ONE,$this->getCompanyClass('MessageSchedule'),'message_id'),
			
		);

	}

	public function log($type,$message)
	{
		$log=MessageLog::factoryByCompany($this->company);
		$log->type=$type;
		$log->body=$message;
		$log->message_id=$this->id;
		$log->save();
	}
}