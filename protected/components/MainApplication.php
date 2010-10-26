<?php

/**
 * Description of MainApplication
 *
 * @author ivan
 */
class MainApplication extends CWebApplication {

    //put your code here
	static public function create($config)
	{
		return Yii::createApplication('MainApplication',$config);
	}

	public function init()
	{
		parent::init();
		if (CONTROLLER_MODE!='admin')
		{
			$this->getUser()->loginUrl = $this->createFrontUrl('user/login');
		}
	}

	public function createFrontUrl($route,$params=array(),$ampersand='&')
	{
		return 'http://'.Yii::app()->params['domain'].$this->createUrl($route, $params, $ampersand);
	}

	public function createCompanyUrl(Company $company,$route,$params=array(),$ampersand='&')
	{
		return 'http://'.$company->host.'.'.Yii::app()->params['domain'].$this->createUrl($route, $params, $ampersand);
	}
}