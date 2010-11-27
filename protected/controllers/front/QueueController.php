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

	public function actionProcessmail()
	{
		header('Content-type: text/plain');

		$queues=QueueMail::model()->with('company')->findAll('status = :status and schedule_on <= :schedule',
				array(':status'=>'created','schedule'=>time()));
		
		foreach ($queues as $queue)
		{
			$queue->status='sending';
			$queue->save(); // like, right now!

			$company=$queue->company;
			$from_mail=$company->info->email_from;
			$from_name=$company->name;

			echo sprintf("Sending mail to %s\n",$queue->to);
			CompanyMailer::sendMessage(
				array($from_mail=>$from_name),
				$queue->to,
				$queue->body,
				$queue->subject
			);

			$queue->status='sent';
			$queue->save();
		}
	}

	
}