<?php


class Event extends CompanyActiveRecord
{
	const MaxOccurrences=370; // why? it's magic numberr :p don't ask
	// Fields that only appear at forms
//	public $start;
//	public $end;
//	public $time_type;
//	public $repeat_type;
//	public $start_time;
//	public $end_time;

	public $repeat_every;
	public $repeat_until;

	public static function baseTableName()
	{
		return 'event';
	}

	public function __set($name,$value)
	{
		$map=array('start_time'=>'start','end_time'=>'end');
		if (isset($map[$name]))
		{
			$var=$map[$name];
			if (is_numeric($this->$var))
				$date=new Zend_Date($this->$var,Zend_Date::TIMESTAMP);
			else
				$date=new Zend_Date($this->$var);
			$time=new Zend_Date(strtotime($value),Zend_Date::TIMESTAMP);
			$date->setHour($time);
			$date->setMinute($time);
			$date->setSecond(0);
			$this->$var=$date->get();
		}
		else
			parent::__set($name,$value);
	}

	public function __get($name)
	{
		$map=array('start_time'=>'start','end_time'=>'end');
		if (isset($map[$name]))
			return $this->$map[$name];
		 else {
			return parent::__get($name);
		 }
	}

//	public function afterFind()
//	{
//		parent::afterFind();
//		$this->start_time=$this->start;
//		$this->end_time=$this->end;
//	}

	public static function modelByCompany(Company $company)
	{
		return parent::modelByCompany($company, __CLASS__);
	}

	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE TABLE IF NOT EXISTS $tableName (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `subject` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL ,
  `time_type` ENUM('normal','fullday','tba','none') NOT NULL ,
  `start` INT NOT NULL ,
  `end` INT NOT NULL ,
  `repeat_type` ENUM('never','daily','weekly','monthly','yearly') NOT NULL ,
  `repeat_every` INT NULL ,
  `repeat_until` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_event_user1` (`user_id` ASC) )
ENGINE = MyISAM";
	}

	protected function afterDelete()
	{
	  Occurrence::modelByCompany($this->company)->deleteAllByAttributes(array('event_id'=>$this->id));
	}

	public function relations()
	{
		$occurrence=Occurrence::modelByCompany($this->company);
		return array(
			'occurrences'=>array(self::HAS_MANY,$this->getCompanyClass('Occurrence'),'event_id',
					'order'=>'occurrences.start'),
			'creator'=>array(self::BELONGS_TO,$this->getCompanyClass('User'),'user_id')
		);
	}

	public function rules()
	{
		return array(
			array('subject,time_type,repeat_type,start,end','required'),
			array('start,end','validDateTime'),
			array('subject','length','min'=>3,'max'=>255),
			array('time_type','in','range'=>array_keys($this->getTimeTypes())),
			array('repeat_type','in','range'=>array_keys($this->getRepeatTypes())),
			array('repeat_every','numerical','integerOnly'=>true,'min'=>1,'max'=>30,'allowEmpty'=>true),
			array('repeat_until','validDateTime','allowEmpty'=>true),
			array('start,end,repeat_until','filter','filter'=>array($this,'dateTimeToTimeStamp'),'on'=>'create,edit'),
			array('start_time,end_time','safe','on'=>'create,edit')
		);
	}

	public function validDateTime($attribute,$params)
	{
		if (isset($params['allowEmpty']) && $params['allowEmpty'] && empty($this->$attribute))
			return;
		try
		{
			$dateTime=new Zend_Date($this->$attribute);
		}
		catch (Zend_Date_Exception $e)
		{
			$this->addError($attribute,'Invalid date');
		}
	}

	public function dateTimeToTimeStamp($dt)
	{
		try
		{
			$dt=new Zend_Date($dt);
			$value=$dt->get();
		}
		catch (Zend_Date_Exception $e)
		{
			$value=null;
		}
		return $value;
	}

	public function beforeSave()
	{
		if (parent::beforeSave())
		{
			$start=new Zend_Date();
			$start->setTimestamp($this->start);
			$end=new Zend_Date();
			$end->setTimestamp($this->end);
			if ($this->time_type!='normal')
			{
				$start->setTime('00:00:00');
				$end->setTime('00:00:00');
			}
			$this->start=$start->get();
			$this->end=$end->get();
			// Start must be greater or equal end *doh*
			if ($start->get()>$end->get())
			{
				$this->addError('end','Ending date must be the same or greater than starting date');
				return false;
			}
			if (!$this->user_id)
				$this->user_id=User::getLoggedUser()->id;
			return true;
		}
		else
			return false;
	}
	
	public function afterSave()
	{
		parent::afterSave();
		
		$occModel=Occurrence::modelByCompany($this->company);
		if ($this->getScenario()=='edit')
			$occModel->deleteAllByAttributes(array('event_id'=>$this->id));

		$occurrence=Occurrence::factoryByCompany($this->company);
		$occurrence->event_id=$this->id;
		$occurrence->start=$this->start;
		$occurrence->end=$this->end;
//		$occurrence->timetype=$this->time_type;
		$occurrence->save();

		if ($this->repeat_type!='never')
		{
			$start=new Zend_Date();
			$start->setTimestamp($this->start);
			$end=new Zend_Date();
			$end->setTimestamp($this->end);
			$until=new Zend_Date();
			$until->setTimestamp($this->repeat_until);
			$until->setTime('23:59:59');
			$occurrences=1;
			switch ($this->repeat_type)
			{
				case 'daily':
					$part=Zend_Date::DAY;
					break;
				case 'weekly':
					$part=Zend_Date::WEEK;
					break;
				case 'monthly':
					$part=Zend_Date::MONTH;
					break;
				case 'yearly':
					$part=Zend_Date::YEAR;
					break;
			}

			while ($occurrences <= self::MaxOccurrences)
			{
				$start->add($this->repeat_every, $part);
				$end->add($this->repeat_every, $part);
				if ($start->get() > $until->get())
					break;
				$occurrence=Occurrence::factoryByCompany($this->company);
				$occurrence->event_id=$this->id;
				$occurrence->start=$start->get();
				$occurrence->end=$end->get();
	//			$occurrence->timetype=$this->time_type;
				$occurrence->save();
			}
		}
	}

	public function getTimeTypes()
	{
		return array(
			'normal'=>'Normal',
			'fullday'=>'Full Day',
			'tba'=>'To Be Announced',
			'none'=>'None'
		);
	}

	public function getRepeatTypes()
	{
		return array(
			'never'=>'Never',
			'daily'=>'Daily',
			'weekly'=>'Weekly',
			'monthly'=>'Monthly',
			'yearly'=>'Yearly');
	}

	public function getRepeatEveryDays()
	{
		static $days=array();
		if (!count($days))
			for ($i=1;$i<=30;$i++)
				$days[$i]=$i;
		return $days;
	}

	public function attributeLabels()
	{
		return array(
			'startdate'=>'From',
			'enddate'=>'To',
			'repeat_every'=>'Repeat every how many days?',
			'repeat_until'=>'Until'
		);
	}

	// Helpers -- from PHP-Calendar
	static public function add_days($stamp, $days)
	{
		return strtotime('+'.$days.' day', $stamp);
	}

	static public function add_months($stamp, $months)
	{
		return strtotime('+'.$months.' month', $stamp);
	}

	static public function add_years($stamp, $years)
	{
		return strtotime('+'.$years.' year', $stamp);
	}
}