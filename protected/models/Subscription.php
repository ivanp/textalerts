<?php

class Subscription extends CompanyActiveRecord
{
	public function primaryKey()
	{
		return array('user_id', 'group_id');
	}

	public static function baseTableName()
	{
		return 'subscription';
	}

	public static function modelByCompany(Company $company)
	{
		return parent::modelByCompany($company, __CLASS__);
	}

	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE TABLE IF NOT EXISTS $tableName (
  `group_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `mail` TINYINT(1) NULL ,
  `text` TINYINT(1) NULL ,
  `since` DATETIME NULL ,
  INDEX `fk_group_users_group1` (`group_id` ASC) ,
  INDEX `fk_group_users_user1` (`user_id` ASC) ,
  INDEX `mail` (`mail` ASC) ,
  INDEX `text` (`text` ASC) )
ENGINE = MyISAM";
	}

	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, $this->getCompanyClass('User'), 'user_id'),
			'group' => array(self::BELONGS_TO, $this->getCompanyClass('Group'), 'group_id'),
		);
	}

	protected function beforeSave()
	{
		if (parent::beforeSave())
		{
			if ($this->mail == 0 && $this->text == 0)
			{
				$this->delete();
				return false;
			}
			else
			{
				if ($this->getIsNewRecord())
					$this->since = date('Y-m-d H:i:s');
				return true;
			}
		}
		else
			return false;
	}
}
