<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class SuperAdminIdentity extends CUserIdentity
{
	private $_id;

	public function getId()
	{
		return $this->_id;
	}

	public function authenticate()
	{
		$user = SuperAdmin::model()->find('LOWER(username) = ?', array(strtolower($this->username)));

		if($user === null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if(sha1($this->password) != $user->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else {
			$this->errorCode=self::ERROR_NONE;
			$this->_id = (int)$user->id;
		}
		return !$this->errorCode;
	}
}