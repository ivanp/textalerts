<?php


class Event extends CompanyActiveRecord
{
	const MaxOccurrences=370; // why? it's magic numberr :p don't ask
	// Fields that only appear at forms
	public $startdate;
	public $starttime;
	public $enddate;
	public $endtime;
	public $time_type;
	public $repeat_type;
	public $repeat_every;
	public $repeat_until;

	public static function baseTableName()
	{
		return 'event';
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
  `user_id` INT NOT NULL ,
  `subject` VARCHAR(255) NULL ,
  `description` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_event_user1` (`user_id` ASC))
ENGINE = MyISAM";
	}

	public function relations()
	{
		return array(
			'occurrences'=>array(self::HAS_MANY,$this->getCompanyClass('Occurrence'),'event_id'),
			'creator'=>array(self::BELONGS_TO,$this->getCompanyClass('User'),'user_id')
		);
	}

	public function rules()
	{
		return array(
			array('subject,time_type,time_type,repeat_type,startdate,enddate','required'),
			array('startdate,enddate','validDate'),
			array('starttime,endtime','validTime','allowEmpty'=>true),
			array('subject','length','min'=>3,'max'=>255),
			array('time_type','in','range'=>array_keys($this->getTimeTypes())),
			array('repeat_type','in','range'=>array_keys($this->getRepeatTypes())),
			array('repeat_every','numerical','integerOnly'=>true,'min'=>1,'max'=>30,'allowEmpty'=>true),
			array('repeat_until','validDate','allowEmpty'=>true),
		);
	}

	public function validDate($attribute,$params)
	{
		if (isset($params['allowEmpty']) && $params['allowEmpty'] && empty($this->$attribute))
			return;
		list($year,$month,$day)=sscanf($this->$attribute,'%04u-%02u-%02u');
		if (!checkdate($month,$day,$year))
			$this->addError($attribute,'Invalid date');
	}

	public function validTime($attribute,$params)
	{
		if (isset($params['allowEmpty']) && $params['allowEmpty'] && empty($this->$attribute))
			return;
		list($hour,$minute)=sscanf($this->$attribute,'%02u-%02u');
		if (!($hour<24 && $minute<60))
			$this->addError($attribute,'Invalid time');
	}



	public function beforeSave()
	{
		if (parent::beforeSave())
		{
			$dt_start=$this->startdate;
			$dt_end=$this->enddate;
			if ($this->time_type=='normal')
			{
				$dt_start.=' '.$this->starttime;
				$dt_end.=' '.$this->endtime;
			}
			else
			{
				// Since we do not require time, assign them null!
				$this->starttime=null;
				$this->endtime=null;
			}
			$dt_start=strtotime($dt_start);
			$dt_end=strtotime($dt_end);
			// Start must be greater or equal end *doh*
			if ($dt_start>$dt_end)
			{
				$this->addError('enddate','Ending date must be the same or greater than starting date');
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
		switch ($this->getScenario())
		{
			case 'insert':
				$occurrence=Occurrence::factoryByCompany($this->company);
				$occurrence->event_id=$this->id;
				$occurrence->startdate=$this->startdate;
				$occurrence->starttime=$this->starttime;
				$occurrence->enddate=$this->enddate;
				$occurrence->endtime=$this->endtime;
				$occurrence->timetype=$this->time_type;
				$occurrence->save();

				$start_time=strtotime($this->startdate.' '.$this->starttime);
				$end_time=strtotime($this->enddate.' '.$this->endtime);
				$until_time=strtotime($this->repeat_until.' 23:59:59');
//				var_dump($this->startdate.' '.$this->starttime,$this->enddate.' '.$this->endtime);
//				exit;
				$occurrences=1;
				switch($this->repeat_type)
				{
					case 'daily':
						$ndays=$this->repeat_every;
						while ($occurrences <= self::MaxOccurrences)
						{
							$start_time=self::add_days($start_time,$ndays);
							$end_time=self::add_days($end_time,$ndays);
//							var_dump(date('Y-m-d H:i:s', $start_time),date('Y-m-d H:i:s', $until_time));
//							exit;
							if ($start_time>$until_time)
								break;
							$occurrence=Occurrence::factoryByCompany($this->company);
							$occurrence->event_id=$this->id;
							$occurrence->timetype=$this->time_type;
							$occurrence->startdate=date('Y-m-d',$start_time);
							$occurrence->enddate=date('Y-m-d',$end_time);
							if ($this->time_type=='normal')
							{
								$occurrence->starttime=date('H:i',$start_time);
								$occurrence->endtime=date('H:i',$end_time);
							}
							$occurrence->save();
							$occurrences++;
						}
						break;
					case 'weekly':
						$ndays=$this->repeat_every*7;
						while ($occurrences <= self::MaxOccurrences)
						{
							$start_time=self::add_days($start_time,$ndays);
							$end_time=self::add_days($end_time,$ndays);
							if ($start_time>$until_time)
								break;
							$occurrence=Occurrence::factoryByCompany($this->company);
							$occurrence->event_id=$this->id;
							$occurrence->timetype=$this->time_type;
							$occurrence->startdate=date('Y-m-d',$start_time);
							$occurrence->enddate=date('Y-m-d',$end_time);
							if ($this->time_type=='normal')
							{
								$occurrence->starttime=date('H:i',$start_time);
								$occurrence->endtime=date('H:i',$end_time);
							}
							$occurrence->save();
							$occurrences++;
						}
						break;
					case 'monthly':
						$nmonths=$this->repeat_every;
						while ($occurrences <= self::MaxOccurrences)
						{
							$start_time=self::add_months($start_time,$nmonths);
							$end_time=self::add_months($end_time,$nmonths);
							if ($start_time>$until_time)
								break;
							$occurrence=Occurrence::factoryByCompany($this->company);
							$occurrence->event_id=$this->id;
							$occurrence->timetype=$this->time_type;
							$occurrence->startdate=date('Y-m-d',$start_time);
							$occurrence->enddate=date('Y-m-d',$end_time);
							if ($this->time_type=='normal')
							{
								$occurrence->starttime=date('H:i',$start_time);
								$occurrence->endtime=date('H:i',$end_time);
							}
							$occurrence->save();
							$occurrences++;
						}
						break;
					case 'yearly':
						$nyears=$this->repeat_every;
						while ($occurrences <= self::MaxOccurrences)
						{
							$start_time=self::add_years($start_time,$nyears);
							$end_time=self::add_years($end_time,$nyears);
							if ($start_time>$until_time)
								break;
							$occurrence=Occurrence::factoryByCompany($this->company);
							$occurrence->event_id=$this->id;
							$occurrence->timetype=$this->time_type;
							$occurrence->startdate=date('Y-m-d',$start_time);
							$occurrence->enddate=date('Y-m-d',$end_time);
							if ($this->time_type=='normal')
							{
								$occurrence->starttime=date('H:i',$start_time);
								$occurrence->endtime=date('H:i',$end_time);
							}
							$occurrence->save();
							$occurrences++;
						}
						break;
				}
				break;
			case 'update':
				break;
		}
	}

	public function getTimeTypes()
	{
		return array(
			'normal'=>'Normal',
			'full'=>'Full Day',
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