<?php

class EventController extends CCompanyController
{
	public function actionCreate()
	{
		$event=Event::factoryByCompany($this->company);
		$varname=get_class($event);
		if (isset($_POST[$varname]))
		{
			$event->attributes=$_POST[$varname];
			if ($event->save())
			{
				Yii::app()->user->setFlash('calendar-index','New event has been created');
				$this->redirect(array('calendar/index'),true);
			}
		}
		else
		{
			$dt = strtotime(date('Y-m-d H:00:00'));
			$dt_from = strtotime('+1 hour', $dt);
			$dt_to = strtotime('+2 hour', $dt);
			// Fill with current date & time
			$event->startdate=date('Y-m-d',$dt_from);
			$event->enddate=date('Y-m-d',$dt_to);
			$event->starttime=date('H:i',$dt_from);
			$event->endtime=date('H:i',$dt_to);
		}

		$this->render('create',array(
			'event'=>$event
		));
	}

	public function actionView($eid,$oid=null)
	{
		$event=Event::modelByCompany($this->company)->findByPk($eid);
		if (!($event instanceof Event))
			throw new CHttpException(404,'Event not found');
		$occurs=$event->occurrences;
		$vars=array('event'=>$event,'occurrences'=>$occurs);
		if (count($occurs)>1 && is_numeric($oid))
		{
			$prev_occur=null;
			$next_occur=null;
			$total_occurs=count($occurs);
			for ($i=0;$i<$total_occurs;$i++)
			{
				if ($occurs[$i]->id==$oid)
				{
					if ($i>0)
						$vars['prev_occur']=$occurs[$i-1];
					if (($i+1)<$total_occurs)
						$vars['next_occur']=$occurs[$i+1];
					$vars['default_occurrence']=$occurs[$i];
					break;
				}
			}
		}

		if (!isset($vars['default_occurrence']))
		{
			$vars['default_occurrence']=current($occurs);
		}
		$this->render('view',$vars);
	}

	public function actionEdit($id)
	{
		$this->render('edit',array());
	}

	public function actionDelete($id)
	{
		$this->render('delete',array());
	}
}