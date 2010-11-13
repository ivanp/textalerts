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
		$start=date('Y-m-d H:i:s',$start);
		$end=date('Y-m-d H:i:s',$end);
		$occurrences=Occurrence::modelByCompany($this->company)->between($start,$end)->findAll();

		$events=array();
		foreach ($occurrences as $occur)
		{
			$start=$occur->startdate;
			$end=$occur->enddate;
			if ($occur->timetype=='normal')
			{
				$allday=false;
				$start.=' '.$occur->starttime;
				$end.=' '.$occur->endtime;
			}
			else
			{
				$allday=true;
			}
			$events[]=array(
				'id'=>$occur->id,
				'title'=>$occur->event->subject,
				'start'=>strtotime($start),
				'end'=>strtotime($end),
				'url'=>$this->createAbsoluteUrl('event/view',array('eid'=>$occur->event->id,'oid'=>$occur->id)),
				'allDay'=>$allday
			);
		}
		echo json_encode($events);
		return;


		$year = date('Y');
		$month = date('m');

		echo json_encode(array(

			array(
				'id' => 111,
				'title' => "Event1",
				'start' => "$year-$month-10",
				'url' => "http://yahoo.com/"
			),

			array(
				'id' => 222,
				'title' => "Event2",
				'start' => "$year-$month-20",
				'end' => "$year-$month-22",
				'url' => "http://yahoo.com/"
			)

		));
	}
}