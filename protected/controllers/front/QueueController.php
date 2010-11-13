<?php

//require_once('Swift/swift_required.php');
Yii::import('ext.swiftMailer.SwiftMailer');

class QueueController extends CFrontController
{
	const QueuesPerRequest = 100;

	public function init()
	{
		parent::init();

		SwiftMailer::init();
	}

	public function actionProcess()
	{
		$queues = MessageQueue::model()->with('company')->findAll(array('limit'=>100));
		if (empty($queues))
			die('No queues');
		$queue_pool = array();
		error_log('Pooling queues');
		foreach ($queues as $q)
		{
			$message = $q->getMessage();
			if ($message->status=='pending')
			{
				// Change to 'sending' rite away!
				$message->status='sending';
				$message->save();
				$queue_pool[] = $q;
				if (count($queue_pool) >= self::QueuesPerRequest)
					break;
			}
		}
		error_log(sprintf('Gathered %d queues in pool', count($queue_pool)));

		$ids = array();
		foreach ($queue_pool as $q)
		{
			$company = $q->company;
			$message = $q->getMessage();
			$group = $message->group;
			foreach ($group->subscribers as $subscriber)
			{
				$user = $subscriber->user;

				if ($subscriber->mail)
					$this->sendMail($message, $user);
				if ($subscriber->text)
					$this->sendText($message, $user);
			}
			// Mark message as sent
			$message->status='sent';
			$message->save();
			// Delete the queue
			$q->delete();
		}
	}

	protected function sendMail(GroupMessage $message, User $user)
	{
		$company = $user->company;
		$company_info = $company->info;
		$from_mail = $company_info->email_from;
		$from_name = $company->name;

		CompanyMailer::sendMessage(array($from_mail=>$from_name),$user->email, $message->body, $company->name);
	}

	protected function sendText(GroupMessage $message, User $user)
	{
		if (!$user->havePhoneNumber() || !$user->phoneNumberConfirmed())
		{
			$message->addLog('error', sprintf('User %d does not have phone number or not confirmed yet', $user->id));
			return;
		}

		$to_mail = $user->phone->getSmsMailGateway();
		CompanyMailer::sendMessage(array($to_mail), $message->body);
	}
}