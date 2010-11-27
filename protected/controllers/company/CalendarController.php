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
		if (is_null($start) || is_null($end))
			throw new CHttpException(400);
//		$start=date('Y-m-d H:i:s',$start);
//		$end=date('Y-m-d H:i:s',$end);
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
				'id'=>$occur->id,
				'title'=>$occur->event->subject,
				'start'=>$occur->start,
				'end'=>$occur->end,
				'url'=>$this->createUrl('event/view',array('eid'=>$occur->event->id,'oid'=>$occur->id)),
				'allDay'=>$allday
			);
		}
		echo CJSON::encode($events);
	}
}