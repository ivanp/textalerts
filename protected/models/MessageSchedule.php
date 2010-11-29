<?php

class MessageSchedule extends CompanyActiveRecord
{
	const TypeOnce='once';
	const TypeRepeat='repeat';

	const RepeatNone='none';
	const RepeatDaily='daily';
	const RepeatWeekly='weekly';
	const RepeatMonthly='monthly';
	const RepeatYearly='yearly';

	public static function baseTableName()
	{
		return 'message_schedule';
	}

	/**
	 *
	 * @param Company $company
	 * @return MessageSchedule
	 */
	public static function modelByCompany(Company $company)
	{
		return parent::modelByCompany($company, __CLASS__);
	}

	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE TABLE IF NOT EXISTS $tableName (
  `message_id` BIGINT UNSIGNED NOT NULL ,
  `start` INT NOT NULL ,
  `repeat_type` ENUM('none','daily','weekly','monthly','yearly') NOT NULL DEFAULT 'none' ,
  `repeat_every` INT UNSIGNED NULL DEFAULT 1 ,
  `repeat_until` INT NULL ,
  INDEX `fk_group_message_schedule_message1` (`message_id` ASC) ,
  PRIMARY KEY (`message_id`) ,
  INDEX `date` (`start` ASC) )
ENGINE = MyISAM";
	}

	public static function getRepeatTypes()
	{
		return array(
			'none'=>'None',
			'daily'=>'Daily',
			'weekly'=>'Weekly',
			'monthly'=>'Monthly',
			'yearly'=>'Yearly'
		);
	}

	public static function getRepeatEveryDays()
	{
		static $days=array();
		if (!count($days))
			for ($i=1;$i<=30;$i++)
				$days[$i]=$i;
		return $days;
	}
	
	public function primaryKey() 
	{
		return 'message_id';
	}

	public function relations()
	{
		return array(
			'message'=>array(self::BELONGS_TO,$this->getCompanyClass('Message'),'message_id')
		);
	}

	protected function beforeSave()
	{
		if (parent::beforeSave())
		{
			if ($this->repeat_type=='none')
			{
				$this->repeat_every=1;
				$this->repeat_until=null;
			}
			return true;
		}
		else
			return false;
	}
	
	/**
	 * Get repeat_until - Zend_Date version
	 * 
	 * @return Zend_Date
	 */
	public function getRepeatUntil()
	{
		if ($this->repeat_until!==null)
			$until_dt=new Zend_Date($this->repeat_until, Zend_Date::TIMESTAMP);
		else
		{
			$until_dt=Zend_Date::now();
			$until_dt->addYear(10); // 10 yrs for now
		}
		$until_dt->setHour(23);
		$until_dt->setMinute(59);
		$until_dt->setSecond(59);
		return $until_dt;
	}
	
	protected function afterSave()
	{
		parent::afterSave();

		$message=$this->message;
		// Rebuild queues
		if (in_array($this->getScenario(), array('create','edit'))) //
		{
			$queueModel=MessageQueue::modelByCompany($this->company);
			// Remove previous
			if ($this->getScenario()=='edit')
				$queueModel->deleteAllByAttributes(array('message_id'=>$this->message_id));

			// Only create queues when status set to pending
			if ($message->status=='pending')
			{
				if ($message->type=='now')
				{
					// No scheduling, send it now!
					$queue=MessageQueue::factoryByCompany($this->company);
					$queue->message_id=$this->message_id;
					$queue->schedule_on=time();
					$queue->status='pending';
					$queue->save();
				}
				else
				{
					// Create queue
					$queue=MessageQueue::factoryByCompany($this->company);
					$queue->message_id=$this->message_id;
					$queue->schedule_on=$this->start;
					$queue->status='pending';
					$queue->save();

					if ($this->repeat_type!=='none')
					{
						// Create schedule queues
						$queue_dt=new Zend_Date($this->start, Zend_Date::TIMESTAMP);
						$until_dt=$this->getRepeatUntil();
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

						$now=new Zend_Date();

						while (true)
						{
							$queue_dt->add($this->repeat_every, $part);
							if ($queue_dt->get() > $until_dt->get())
								break;
							$queue=MessageQueue::factoryByCompany($this->company);
							$queue->message_id=$this->message_id;
							$queue->schedule_on=$queue_dt->get();
							$queue->status='pending';
							$queue->save();
						}
					}
				}
			}

		}
	}
}