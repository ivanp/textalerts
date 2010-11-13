<?php

class AdminWebUser extends CWebUser
{
	private $_allowForwardVars = array('username','email');
	private $_user;

	/**
	 *
	 * @return SuperAdmin
	 */
	public function getRecord()
	{
		//var_dump($this->getIsGuest());
		if (!$this->getIsGuest() && !isset($this->_user))
		{
			$this->_user = SuperAdmin::model()->findByPk($this->getId());
		}
		return $this->_user;
	}

	public function  __get($name)
	{
		if (in_array($name, $this->_allowForwardVars))
		{
			$user = $this->getRecord();
			return $user->$name;
		}
		else
			return parent::__get($name);
	}
}