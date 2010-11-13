<?php

Yii::import('ext.swiftMailer.SwiftMailer');

class CompanyMailer extends CComponent
{
	const QueuesPerRequest = 100;
	const MailsPerMinute = 100;
	
	static private $_mailer;

	/**
	 * @return Swift_Mailer
	 */
	static public function instance()
	{
		if (!isset(self::$_mailer))
		{
			SwiftMailer::init();
			$transport = Swift_MailTransport::newInstance();
			self::$_mailer = Swift_Mailer::newInstance($transport);

			self::$_mailer->registerPlugin(new Swift_Plugins_ThrottlerPlugin(
				self::MailsPerMinute, Swift_Plugins_ThrottlerPlugin::MESSAGES_PER_MINUTE
			));
		}

		return self::$_mailer;
	}

	static public function sendMessage($from, $to, $body, $subject=null)
	{
		$instance = self::instance();
		$company = Yii::app()->getCompany();
		$company_info = $company->info;
		if ($subject===null)
			$subject=$company_info->title;
		$message = Swift_Message::newInstance($subject)
			->setFrom($from)
			->setTo($to)
			->setBody($body)
			;

		$instance->send($message);
	}
}