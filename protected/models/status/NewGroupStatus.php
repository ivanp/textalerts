<?php

class NewGroupStatus extends StatusUpdate
{
	public function setGroup(Group $group)
	{
		$this->_params['group_id']=$group->id;
	}
	
	public function getGroup()
	{
		return Group::modelByCompany($this->company)->findByPk($this->_params['group_id']);
	}
}