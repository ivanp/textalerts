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


		$domain = Yii::app()->params['domain'];
		$current_baseurl = Yii::app()->getRequest()->getHostInfo();
		$pattern = '#(\w+)\.'. preg_quote($domain, '#').'#i';
		if (!preg_match($pattern, $current_baseurl, $result))
		{
			$this->redirect($this->createFrontUrl('site/invaliddomain'), true, 301);
		}
		$host = $result[1];
		if (in_array($host, Yii::app()->params['redirectHosts']))
		{
			$this->redirect($this->createFrontUrl('site/index'), true, 301);
		}
		$company = Company::model()->find('host = :host', array(':host' => $host));
		if ($company instanceof Company)
			$this->company = $company;
		else
			$this->redirect($this->createFrontUrl('site/invalidhost'), true, 302);

		$isLoggedIn = !Yii::app()->user->isGuest;
		$isSender = $isLoggedIn && $company->isSender(Yii::app()->user->record);
		$isAdmin = $isLoggedIn && $company->isAdministrator(Yii::app()->user->record);

		// Setup menu
		$this->menu = array(
				array('label'=>'Home', 'url'=>array('site/index')),
//				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
//				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Dashboard', 'url'=>array('site/dashboard'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Groups', 'url'=>array('group/index'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Send Alert', 'url'=>array('message/create'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Community Calendar', 'url'=>array('calendar/index'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Settings', 'url'=>array('company/settings'), 'visible'=>!Yii::app()->user->isGuest),
//				array('label' => 'Control Center', 'url'=>array('/admin'), 'visible' => (!Yii::app()->user->isGuest && Yii::app()->user->record->admin == 'Y')),
				array('label'=>'Login', 'url'=>$this->createFrontUrl('user/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Register', 'url'=>$this->createFrontUrl('user/register'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Account ('.Yii::app()->user->name.')', 'url'=>array('/user/account'), 'visible'=>!Yii::app()->user->isGuest, 'items' => array(
						array('label' => 'Profile', 'url'=>$this->createFrontUrl('user/profile')),
						array('label' => 'Password', 'url'=>$this->createFrontUrl('user/password')),
						array('label' => 'Logout', 'url'=>$this->createFrontUrl('user/logout'))
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