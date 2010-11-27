<?php

class MessageQueue extends CompanyActiveRecord
{
	const StatusPending='pending';
	const StatusProgress='progress';
	const StatusSent='sent';

	public static function baseTableName()
	{
		return 'message_queue';
	}

	public static function modelByCompany(Company $company)
	{
		return parent::modelByCompany($company, __CLASS__);
	}

	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE TABLE IF NOT EXISTS $tableName (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `message_id` BIGINT UNSIGNED NOT NULL ,
  `schedule_on` INT NOT NULL ,
  `status` ENUM('pending','progress','sent') NOT NULL ,
  `last_retry` INT NULL ,
  `sent_on` INT NULL ,
  INDEX `fk_group_message_occur_message1` (`message_id` ASC) ,
  PRIMARY KEY (`id`) ,
  INDEX `schedule` (`schedule_on` ASC) ,
  INDEX `status` (`status` ASC))
ENGINE = MyISAM";
	}

	protected function afterSave()
	{
		parent::afterSave();

		// Let's create the REAL mail & text queue
		$message=$this->message;
		$schedule=$this->schedule;
		$company=$this->company;
		$company_info=$company->info;

		$user_pool=array();
		// Main group loop
		foreach ($message->recipients as $group)
		{
			foreach ($group->subscribers as $subscriber)
			{
				$user=$subscriber->user;
				if (in_array($user->id,$user_pool))
					continue;
				$user_pool[]=$user->id;
				if ($subscriber->mail)
				{
					$queue=new QueueMail();
					$queue->company_id=$this->company->id;
					$queue->to=$user->email;
					$queue->subject=$company_info->title;
					$queue->from=$company_info->email_from;
					$queue->body=$message->body;
					$queue->schedule_on=$this->schedule_on;
					$queue->status='created';
					$queue->save();
				}
				if ($subscriber->text && $user->isPhoneConfirmed())
				{
					/* COMMENTED TEMPORARILY */
//					$queue=new QueueText();
//					$queue->company_id=$this->company->id;
//					$queue->body=$message->body;
//					$queue->schedule_on=$this->schedule_on;
//					$queue->status='created';
//					$queue->number=$user->phone->number;
//					$queue->carrier_id=$user->phone->carrier_id;
//					$queue->save();
					$queue=new QueueMail();
					$queue->company_id=$this->company->id;
					$queue->to=$user->phone->getSmsMailGateway();
					$queue->subject=$company_info->title;
					$queue->from=$company_info->email_from;
					$queue->body=$message->body;
					$queue->schedule_on=$this->schedule_on;
					$queue->status='created';
					$queue->save();
				}
			}
		}
	}

	public function relations()
	{
		return array(
			'message'=>array(self::BELONGS_TO,$this->getCompanyClass('Message'),'message_id'),
			'schedule'=>array(self::BELONGS_TO,$this->getCompanyClass('MessageSchedule'),'message_id')
		);
	}
}