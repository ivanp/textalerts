<?php

class Message extends CompanyActiveRecord
{
	const SendTypeNow='now';
	const SendTypeSchedule='schedule';

	const StatusDraft='draft';
	const StatusPending='pending';
	const StatusProgress='progress';
	const StatusSent='sent';
	
	/**
	 * Group recipient
	 * 
	 * @var array
	 */
	public $groups=array();

	public $start; // unixtime

	public $repeatType;
	public $repeatEvery;
	public $repeatUntil; // unixtime

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
  `created_on` INT NOT NULL ,
  `updated_on` INT NULL ,
  `status` ENUM('draft','pending','progress','sent') NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_message_user1` (`user_id` ASC) ,
  INDEX `type` (`type` ASC) ,
  INDEX `status` (`status` ASC) )
ENGINE = MyISAM";
	}

	public static function getTypeOptions()
	{
		return array(
			'now'=>'Send right away after I click "Start Sending"',
			'schedule'=>'Let me setup scheduling.'
		);
	}

	public function rules()
	{
		return array(
			array('body,type','required'),
			array('type','in','range'=>array('now','schedule')),
			array('body','length','max'=>160),
			array('user_id','unsafe'),
			array('groups','required','on'=>'create,edit'),
			array('start,repeatUntil','filter','filter'=>array($this,'dateTimeToTimeStamp'),'on'=>'create,edit'),
			array('repeatType,repeatEvery,repeatUntil','safe','on'=>'create,edit')
//			array('start','validDateTime','allowEmpty'=>true,'on'=>'create,edit'),
//			array('repeatUntil','validDateTime','allowEmpty'=>true,'on'=>'create,edit'),
//			array('repeatUntil','numerical','integerOnly'=>true,'min'=>0,'allowEmpty'=>true),
			//array('startDateTime','validDateTime','allowEmpty'=>true)
//			array('startDate,repeatUntil','validDate','allowEmpty'=>true),
//			array('startTime','validTime','allowEmpty'=>true)
		);
	}

/*
 * 	public $startDate;
	public $startTime;

	public $repeatType;
	public $repeatEvery;
	public $repeatUntil;
 */

	public function dateTimeToTimeStamp($value)
	{
		try
		{
			$dt=new Zend_Date($value);
			$value=$dt->get();
		}
		catch (Zend_Date_Exception $e)
		{
			$value=null;
		}
		return $value;
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
//
//	public function validTime($attribute,$params)
//	{
//		if (isset($params['allowEmpty']) && $params['allowEmpty'] && empty($this->$attribute))
//			return;
//		list($hour,$minute)=sscanf($this->$attribute,'%02u:%02u');
//		if (!(is_numeric($hour) && is_numeric($minute)) || !($hour<24 && $minute<60))
//			$this->addError($attribute,'Invalid time');
//	}

	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			// Only do these at 'create' or 'edit' scenario
			if (in_array($this->getScenario(),array('create','edit')))
			{
				// Schedule message only
				if ($this->type==self::SendTypeSchedule)
				{
					$now=time();
					// Start time must be in the future
					if ($this->start < $now)
					{
						$this->addError('start','Cannot schedule on the past');
						return false;
					}
					// "Repeat until" must be ahead of start time
					if ($this->repeatType != MessageSchedule::RepeatNone &&  
							is_numeric($this->repeatUntil))
					{
						$until_dt=new Zend_Date($this->repeatUntil,Zend_Date::TIMESTAMP);
						$until_dt->setHour(23);
						$until_dt->setMinute(59);
						$until_dt->setSecond(59);
						if ($until_dt->get() < $this->start)
						{
							$this->addError('repeatUntil','Repeat until must be greater than start time');
							return false;
						}
					}
				}
			}
			if ($this->getIsNewRecord())
			{
				$this->created_on=time();
				$this->user_id=User::getLoggedUser()->id;
			}
			else
				$this->updated_on=time();

//			if (in_array($this->getScenario(), array('create','edit')))
//			{
//				if ($this->type==self::SendTypeSchedule)
//				{
//					try
//					{
//						$start_dt=new Zend_Date($this->startDateTime);
//						$this->start=$start_dt->get();
//					}
//					catch (Zend_Date_Exception $e)
//					{
//						$this->addError('startDateTime', 'Invalid date');
//						return false;
//					}
//				}
//			}

			return true;
		}
		else
			return false;
	}
	
	protected function afterFind() 
	{
		parent::afterFind();
		foreach ($this->recipients as $group)
			$this->groups[] = $group->id;
		$schedule=$this->schedule;
		$this->start=$schedule->start;
		$this->repeatType=$schedule->repeat_type;
		$this->repeatEvery=$schedule->repeat_every;
		$this->repeatUntil=$schedule->repeat_until;
	}
	
	protected function afterDelete() 
	{
		parent::afterDelete();
		$this->deleteRelatedRows();
	}
	
	protected function deleteRelatedRows()
	{
		MessageSchedule::modelByCompany($this->company)
						->deleteAllByAttributes(array('message_id'=>$this->id));
		MessageGroup::modelByCompany($this->company)
						->deleteAllByAttributes(array('message_id'=>$this->id));
		$queues=MessageQueue::modelByCompany($this->company)->findAllByAttributes(array('message_id'=>$this->id));
		foreach ($queues as $queue)
			$queue->delete(); // Must delete manually per-row to execute afterDelete()
	}

	protected function afterSave()
	{
		parent::afterSave();
		if ($this->getScenario()=='edit') 
			$this->deleteRelatedRows();
		// Insert groups
		$this->rebuildGroup();
		// Insert Schedule
		$schedule=MessageSchedule::factoryByCompany($this->company);
		$schedule->setScenario('create');
		$schedule->message_id=$this->id;
		if ($this->type=='schedule')
		{
			$schedule->start=$this->start;
			$schedule->repeat_type=$this->repeatType;
			$schedule->repeat_every=$this->repeatEvery;
			$schedule->repeat_until=$this->repeatUntil;
		}
		else
		{
			$schedule->start=time();
			$schedule->repeat_type='none';
		}
		$schedule->save();
		$this->schedule=$schedule;
	}

//	public function rebuildQueue()
//	{
//		$queueModel=MessageQueue::modelByCompany($this->company);
//		$scheduleModel=MessageSchedule::modelByCompany($this->company);
//		// Delete all previously created queues
//		$queueModel->deleteAllByAttributes(array('message_id'=>$this->id));
//		if ($this->type=='now')
//		{
//			// Remove schedule data since we're no longer need'em
//		  $scheduleModel->deleteAllByAttributes(array('message_id'=>$this->id));
//		}
////		$this->generateQueue();
////	}
////
////	public function generateQueue()
////	{
////		$queueModel=MessageQueue::modelByCompany($this->company);
//
//		if ($this->type=='now')
//		{
//			// No scheduling, send it now!
//			$queue=MessageQueue::factoryByCompany($this->company);
//			$queue->message_id=$this->id;
//			$queue->schedule_on=time();
//			$queue->status='pending';
//			$queue->save();
//		}
//		else
//		{
//			// Prep date variables for comparisons etc
//			$start_dt=new Zend_Date($this->start, Zend_Date::TIMESTAMP);
//
//			// If repeatUntil was set
//			if ($this->repeatUntil!==null)
//			{
//				$until_dt=new Zend_Date($this->repeatUntil, Zend_Date::TIMESTAMP);
//				$until_dt->setHour(23);
//				$until_dt->setMinute(59);
//				$until_dt->setSecond(59);
//			}
//			else
//			{
//				$until_dt=Zend_Date::now();
//				switch ($this->repeatType)
//				{
//					case 'daily':
//					case 'weekly':
//						$until_dt->addYear(2);
//						break;
//					case 'monthly':
//						$until_dt->addYear(5);
//						break;
//					case 'yearly':
//						$until_dt->addYear(10);
//						break;
//				}
//			}
//
//			$queue=MessageQueue::factoryByCompany($this->company);
//			$queue->message_id=$this->id;
//			$queue->schedule_on=$this->start;
//			$queue->status='pending';
//			$queue->save();
//
//			$queue_dt=clone $start_dt;
//
//			if ($this->repeatType=='none')
//				return;
//			switch ($this->repeatType)
//			{
//				case 'daily':
//
//			}
//
//			while (true)
//			{
//				
//			}
//
//			switch($this->repeatType)
//			{
//				case 'daily':
//					while (true)
//					{
//						$queue_dt->addDay($this->repeatEvery);
//						if ($queue_dt->get() > $until_dt->get())
//							break;
//						if (!$queueModel->exists('message_id=:msg_id and schedule_on=:date',
//								array(':msg_id'=>$this->id,':date'=>$full_date->date)))
//						{
//							$queue=MessageQueue::factoryByCompany($this->company);
//							$queue->message_id=$this->id;
//							$queue->schedule_on=$queue_dt->get();
//							$queue->status='pending';
//							$queue->save();
//						}
//					}
//					break;
//				case 'weekly':
//					while (true)
//					{
//						$queue_dt->addWeek($this->repeatEvery);
//						if ($queue_dt->get() > $until_dt->get())
//							break;
//						if (!$queueModel->exists('message_id=:msg_id and schedule_on=:date',
//								array(':msg_id'=>$this->id,':date'=>$full_date->date)))
//						{
//							$queue=MessageQueue::factoryByCompany($this->company);
//							$queue->message_id=$this->id;
//							$queue->schedule_on=$queue_dt->get();
//							$queue->status='pending';
//							$queue->save();
//						}
//					}
//					break;
//				case 'monthly':
//					while (true)
//					{
//						$queue_dt->addMonth($this->repeatEvery);
//						if ($queue_dt->get() > $until_dt->get())
//							break;
//						if (!$queueModel->exists('message_id=:msg_id and schedule_on=:date',
//								array(':msg_id'=>$this->id,':date'=>$full_date->date)))
//						{
//							$queue=MessageQueue::factoryByCompany($this->company);
//							$queue->message_id=$this->id;
//							$queue->schedule_on=$queue_dt->get();
//							$queue->status='pending';
//							$queue->save();
//						}
//					}
//					break;
//				case 'yearly':
//					while (true)
//					{
//						$queue_dt->addYear($this->repeatEvery);
//						if ($queue_dt->get() > $until_dt->get())
//							break;
//						if (!$queueModel->exists('message_id=:msg_id and schedule_on=:date',
//								array(':msg_id'=>$this->id,':date'=>$full_date->date)))
//						{
//							$queue=MessageQueue::factoryByCompany($this->company);
//							$queue->message_id=$this->id;
//							$queue->schedule_on=$queue_dt->get();
//							$queue->status='pending';
//							$queue->save();
//						}
//					}
//					break;
//				default: // No repeating
//			}
//		}
//	}

	public function rebuildGroup()
	{
		if (in_array($this->getScenario(),array('create','edit')))
		{
			$groupModel=Group::modelByCompany($this->company);
			$gmModel=MessageGroup::modelByCompany($this->company);
			// Remove all previous rows
			$gmModel->deleteAllByAttributes(array('message_id'=>$this->id));
			foreach ($this->groups as $gid)
			{
				if ($groupModel->exists('id=:id',array(':id'=>$gid)))
				{
					$gm=MessageGroup::factoryByCompany($this->company);
					$gm->group_id=$gid;
					$gm->message_id=$this->id;
					$gm->save();
				}
			}
		}
	}

	public function attributeLabels()
	{
		return array(
			'body'=>'Message content',
			'type'=>'When would you like to send?',
			'start'=>'Time to send message',
			'repeatType'=>'Repeat'
		);
	}

	public function relations()
	{
		$groupModel=Group::modelByCompany($this->company);
		$msgGroupModel=MessageGroup::modelByCompany($this->company);
		$userModel=User::modelByCompany($this->company);
		return array(
			'recipients'=>array(self::MANY_MANY,get_class($groupModel),$msgGroupModel->tableName().'(message_id,group_id)'),
			'logs'=>array(self::HAS_MANY,$this->getCompanyClass('MessageLog'),'message_id'),
			'schedule'=>array(self::HAS_ONE,$this->getCompanyClass('MessageSchedule'),'message_id'),
			'queues'=>array(self::HAS_MANY,$this->getCompanyClass('MessageQueue'),'message_id',
					'order'=>'schedule_on')
		);
	}

	/**
	 * @return MessageQueue
	 */
	public function getLastRunningQueue()
	{
		$criteria=new CDbCriteria();
		$criteria->limit=1;
		$criteria->order='schedule_on DESC';
		$criteria->condition='schedule_on < :timeNow';
		$criteria->params=array(
			':timeNow'=>time()
		);
		return MessageQueue::modelByCompany($this->company)->with('message_count')->find($criteria);
	}

	public function getNextRunningQueue()
	{
		$criteria=new CDbCriteria();
		$criteria->limit=1;
		$criteria->order='schedule_on ASC';
		$criteria->condition='schedule_on > :timeNow';
		$criteria->params=array(
			':timeNow'=>time()
		);
		return MessageQueue::modelByCompany($this->company)->with('message_count')->find($criteria);
	}

	public function log($type,$message)
	{
		$log=MessageLog::factoryByCompany($this->company);
		$log->type=$type;
		$log->body=$message;
		$log->message_id=$this->id;
		$log->save();
	}

	public function getUntilDate()
	{
		return empty($this->repeatUntil)
			? ''
			: date('Y-m-d H:i:s', $this->repeatUntil);
	}

	public function setUntilDate($value)
	{
		$this->repeatUntil=strtotime($value);
	}
}