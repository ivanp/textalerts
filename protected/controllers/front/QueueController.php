<?php

//require_once('Swift/swift_required.php');
Yii::import('ext.swiftMailer.SwiftMailer');

class QueueController extends CFrontController
{
	const QueuesPerRequest = 100;
	const MailsPerMinute = 100;


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
		$message = Swift_Message::newInstance('Wonderful Subject')
			->setFrom(array('ivan@primaguna.com' => 'Ivan P'))
			->setTo(array($user->email => $user->getDisplayName()))
			->setBody($message->body)
			;

		$this->mailerInstance()->send($message);
	}

	protected function sendText(GroupMessage $message, User $user)
	{
		if (!$user->havePhoneNumber() || !$user->phoneNumberConfirmed())
		{
			$message->addLog('error', sprintf('User %u does not have phone number or not confirmed yet', $user->id));
			return;
		}

		$message = Swift_Message::newInstance('Wonderful Subject')
			->setFrom(array('ivan@primaguna.com' => 'Ivan P'))
			->setTo(array($user->email => $user->getDisplayName()))
			->setBody($message->body)
			;

		$this->mailerInstance()->send($message);
	}

	protected function mailerInstance()
	{
		static $mailer;

		if (!isset($mailer))
		{
			$transport = Swift_MailTransport::newInstance();
			$mailer = Swift_Mailer::newInstance($transport);

			$mailer->registerPlugin(new Swift_Plugins_ThrottlerPlugin(
				self::MailsPerMinute, Swift_Plugins_ThrottlerPlugin::MESSAGES_PER_MINUTE
			));
		}

		return $mailer;
	}
}