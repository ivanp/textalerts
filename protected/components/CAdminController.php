<?php

class CAdminController extends Controller
{
	public $layout='//layouts/column1';

	public function init()
	{
		
	}

	public function filters()
	{
		return array(
			'accessControl'
		);
	}

	public function accessRules()
	{
		return array(
				// Allow only Super Administrators
//				array('allow',
//						'roles'=>array('SuperAdministrator')
//				),
//				// Kick out all other users
//				array('deny',
//						'users'=>array('*'),
//				),
		);
	}
}