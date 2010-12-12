<?php

class CalendarController extends CCompanyController
{
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionEvents()
	{
		$start=isset($_GET['start']) ? $_GET['start'] : null;
		$end=isset($_GET['end']) ? $_GET['end'] : null;
		if (!is_numeric($start) || !is_numeric($end))
			throw new CHttpException(400);
		$client_offset=isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
		$client_offset=$client_offset*60;
		$server_offset=Zend_Date::now()->get(Zend_Date::TIMEZONE_SECS);

		$occurrences=Occurrence::modelByCompany($this->company)->between($start,$end)->findAll();

		$events=array();
		foreach ($occurrences as $occur)
		{
			$event=$occur->event;
			if ($event->time_type=='normal')
				$allday=false;
			else
				$allday=true;
			$events[]=array(
				'id'=>$occur->event->id,
				'title'=>$occur->event->subject,
				'start'=>$occur->start + $client_offset + $server_offset,
				'end'=>$occur->end + $client_offset + $server_offset,
				'url'=>$this->createUrl('event/view',array('eid'=>$occur->event->id,'oid'=>$occur->id)),
				'allDay'=>$allday
			);
		}
		echo CJSON::encode($events);
	}
}