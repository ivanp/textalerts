<?php

class NewEventStatus extends StatusUpdate
{
	const Message='%s created new event: <a href="%s">%s</a>';
	
	public function setEvent(Event $event)
	{
		$this->_params['event']=$event->id;
	}
	
	public function getEvent()
	{
		return Event::modelByCompany($this->company)->findByPk($this->_params['event']);
	}
	
	public function getStatusMessage()
	{
		$event=$this->getEvent();
		$url=Yii::app()->createCompanyUrl($this->company,'event/view',array('id'=>$event->id));
		return sprintf(self::Message,$this->user->getDisplayName(),$url,$event->subject);
	}
}