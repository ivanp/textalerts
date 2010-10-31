<?php

class CompanyWebUser extends CWebUser
{
	public function getRecord()
	{
		return User::getLoggedUser();
	}
}