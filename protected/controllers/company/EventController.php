<?php

class EventController extends CCompanyController
{
	public function actionCreate()
	{
		if (Yii::app()->user->isGuest || !$this->company->isAdministrator(Yii::app()->user->record))
			throw new CHttpException(401,'Access Denied');
		$event=Event::factoryByCompany($this->company);
		$event->setScenario('create');
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
			$dt=new Zend_Date();
			$dt->setMinute(0);
			$dt->setSecond(0);
			$dt->addHour(1);
			$event->start=$dt->get();
			$dt->addHour(1);
			$event->end=$dt->get();
		}

		$this->render('create',array(
			'event'=>$event
		));
	}

	public function actionEdit($id)
	{
		if (Yii::app()->user->isGuest || !$this->company->isAdministrator(Yii::app()->user->record))
			throw new CHttpException(401,'Access Denied');

		$event=Event::modelByCompany($this->company)->with('occurrences')->findByPk($id);
		if (!($event instanceof Event))
			throw new CHttpException(404);
		$event->setScenario('edit');
		$varname=get_class($event);
		if (isset($_POST[$varname]))
		{
			$event->attributes=$_POST[$varname];
			if ($event->save())
			{
				Yii::app()->user->setFlash('calendar-index','Event has been updated');
				$this->redirect(array('calendar/index'),true);
			}
		}

		$this->render('edit',array(
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

	public function actionDelete($id)
	{
		if (!$this->company->isAdministrator(Yii::app()->user->record))
			throw new CHttpException(401,'Access Denied');
		$event=Event::modelByCompany($this->company)->with('occurrences')->findByPk($id);
		if (!($event instanceof Event))
			throw new CHttpException(404);
		$event->delete();
		Yii::app()->user->setFlash('calendar-index','Event has been deleted');
		$this->redirect(array('calendar/index'),true);
	}
}