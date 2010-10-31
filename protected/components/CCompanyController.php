<?php

class CCompanyController extends Controller
{
	public $layout='//layouts/column1';
	
	/**
	 *
	 * @var Company
	 */
	public $company;

	public function init()
	{
		parent::init();


		$this->company = Yii::app()->getCompany();

		$isLoggedIn = !Yii::app()->user->isGuest;
		$user = User::getLoggedUser();
		$isAdmin = $isLoggedIn && $this->company->isAdministrator($user);
		$isSender = $isAdmin && $this->company->isSender($user);

		// Setup menu
		$this->menu = array(
				array('label'=>'Home', 'url'=>array('site/index')),
				array('label'=>'Dashboard', 'url'=>array('site/dashboard'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Groups', 'url'=>array('group/index'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Send Alert', 'url'=>array('message/create'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Community Calendar', 'url'=>array('calendar/index'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Settings', 'url'=>array('company/settings'), 'visible'=>$isAdmin),
				array('label'=>'Login', 'url'=>$this->createFrontUrl('user/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Register', 'url'=>$this->createFrontUrl('user/register'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Account ('.Yii::app()->user->name.')', 'url'=>array('/user/account'), 'visible'=>!Yii::app()->user->isGuest, 'items' => array(
						array('label' => 'Profile', 'url'=>array('user/profile')),
						array('label' => 'Password', 'url'=>array('user/password')),
						array('label' => 'Logout', 'url'=>array('user/logout'))
				)),
			);
		
	}

	public function createAbsoluteUrl($route,$params=array(),$schema='',$ampersand='&')
	{
		return Yii::app()->createCompanyUrl($this->company,$route,$params,$schema,$ampersand);
	}

	public function renderPartial($view,$data=null,$return=false,$processOutput=false)
	{
		if (!is_array($data))
			$data = array();
		$data['company'] = $this->company;
		return parent::renderPartial($view,$data,$return,$processOutput);
	}
}