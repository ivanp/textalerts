<?php

Yii::import('application.models.status.*');

class StatusUpdate extends CompanyActiveRecord
{
	protected $_params=array();
	
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
  `time` INT NULL ,
  `user_id` INT NOT NULL ,
  `type` VARCHAR(50) NULL ,
  `params` LONGTEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_status_update_user1` (`user_id` ASC) ,
  INDEX `time` (`time` DESC) )
ENGINE = MyISAM";
	}
	
	public function rules()
	{
		return array(
			array('type','required'),
		);
	}
	
	public function getMessage()
	{
		$class=$this->type;
		if (!class_exists($class))
			$class='BaseStatusLog';
	}
	
	protected function instantiate($attributes)
	{
		$class=$attributes['type'];
		$model=new $class(null);
		return $model;
	}
	
	public function relations()
	{
		return array(
			'user'=>array(self::BELONGS_TO,$this->getCompanyClass('User'),'user_id')
		);
	}
	
	public function getStatusMessage()
	{
		return '';
	}
	
	protected function afterFind() 
	{
		parent::afterFind();
		$this->_params=unserialize($this->params);
	}
	
	protected function beforeSave() 
	{
		if (parent::beforeSave())
		{
			$this->params=serialize($this->_params);
			return true;
		}
		else
			return false;
	}
}