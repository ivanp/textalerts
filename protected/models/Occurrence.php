<?php

class Occurrence extends CompanyActiveRecord
{
	public static function baseTableName()
	{
		return 'occurrence';
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
  `event_id` INT NOT NULL ,
  `startdate` DATE NULL ,
  `enddate` DATE NULL ,
  `starttime` TIME NULL ,
  `endtime` TIME NULL ,
  `timetype` ENUM('normal','full','tba','none') NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_table1_event1` (`event_id` ASC) ,
  INDEX `startdate` (`startdate` ASC) ,
  INDEX `enddate` (`enddate` ASC) )
ENGINE = MyISAM";
	}

	public function relations()
	{
		return array(
			'event'=>array(self::BELONGS_TO, $this->getCompanyClass('Event'), 'event_id')
		);
	}

	public function rules()
	{
		return array(
			array('timetype','in','range'=>array('normal','full','tda','none'))
		);
	}

	public function scopes()
	{
		return array(
//			'published'=>array(
//					'condition'=>'status=1',
//			),
//			'upcoming'=>array(
//					'order'=>'startdate DESC',
//					'limit'=>10,
//			),
		);
	}

	public function between($start,$end)
	{
		$this->getDbCriteria()->mergeWith(array(
			'condition'=>sprintf("startdate BETWEEN CAST('%s' AS DATETIME) AND CAST('%s' AS DATETIME)",$start,$end),
			'order'=>'startdate ASC'
    ));
    return $this;
	}

	public function upcoming($limit=10)
	{
		$this->getDbCriteria()->mergeWith(array(
			'condition'=>"startdate >= NOW()",
			'order'=>'startdate ASC',
			'limit'=>$limit,
			'group'=>'event_id ASC'
    ));
    return $this;
	}
}

