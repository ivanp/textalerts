<?php

class CAdminController extends Controller
{
	public $layout='//layouts/column1';
	public $menu=array();

	public function init()
	{
		parent::init();

		if (!Yii::app()->user->isGuest)
		{
			$this->menu = array(
				array('label'=>'Home', 'url'=>$this->createUrl('/site/index')),
				array('label'=>'Companies', 'url'=>$this->createUrl('/company/index')),
				array('label'=>'Access Control', 'url'=>array('/srbac')),
				array('label'=>'Logs', 'url'=>$this->createUrl('/log/index')),
				array('label'=>sprintf('Logout (%s)', Yii::app()->user->username), 'url'=>$this->createUrl('/user/logout')),
			);
		}
		else
		{
			$this->menu = array(
				array('label'=>'Login', 'url'=>$this->createUrl('/user/login'))
			);
		}
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
				array('allow',
						'controllers'=>array('user'),
						'actions'=>array('login')
				),
				array('allow',
						'roles'=>array('SuperAdministrator')
				),
				// Kick out all other users
				array('deny',
						'users'=>array('*'),
				),
		);
	}
}