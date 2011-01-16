<?php

class MessageSentStatus extends StatusUpdate
{
	public function setMessageQueue(MessageQueue $message_queue)
	{
		$this->_params['mq_id']=$message_queue->id;  
	}
	
	public function getMessageQueue()
	{
		return MessageQueue::modelByCompany($this->company)
						->with('message,message_schedule')
						->findByPk($this->_params['mq_id']);
	}
}