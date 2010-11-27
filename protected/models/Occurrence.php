<?php

class Occurrence extends CompanyActiveRecord
{
	private $startDate;
	private $endDate;

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
  `start` INT NULL ,
  `end` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_table1_event1` (`event_id` ASC) ,
  INDEX `startdate` (`start` ASC) )
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
//			array('time_type','in','range'=>array('normal','full','tda','none'))
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
			'condition'=>sprintf("start BETWEEN %d AND %d",$start,$end),
			'order'=>'start ASC'
    ));
    return $this;
	}

	public function upcoming($limit=10)
	{
		$this->getDbCriteria()->mergeWith(array(
			'condition'=>"start >= ".time(),
			'order'=>'start ASC',
			'limit'=>$limit,
			'group'=>'event_id ASC'
    ));
    return $this;
	}

	public function getStartDate()
	{
		if (!isset($this->startDate))
		{
			$this->startDate=new Zend_Date();
			$this->startDate->setTimestamp($this->start);
		}
		return $this->startDate;
	}

	public function getEndDate()
	{
		if (!isset($this->endDate))
		{
			$this->endDate=new Zend_Date();
			$this->endDate->setTimestamp($this->end);
		}
		return $this->endDate;
	}
}
