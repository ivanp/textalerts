<?php

/**
 * Description of MainApplication
 *
 * @author ivan
 */
class MainApplication extends CWebApplication {

	/**
	 *
	 * @var Company
	 */
	private $_company;

    //put your code here
	static public function create($config)
	{
		return Yii::createApplication('MainApplication',$config);
	}

	public function init()
	{
		parent::init();

		if (CONTROLLER_MODE == 'company')
		{
			$current_baseurl = $this->getRequest()->getHostInfo();
			$pattern = '#(\w+)\.'. preg_quote($this->params['domain'], '#').'#i';
			if (!preg_match($pattern, $current_baseurl, $result))
				$this->getRequest()->redirect($this->createFrontUrl('site/invaliddomain'), true, 301);

			$host = $result[1];
			if (in_array($host, $this->params['redirectHosts']))
				$this->getRequest()->redirect($this->createFrontUrl('site/index'), true, 301);

			$company = Company::model()->find('host = :host', array(':host' => $host));
			if ($company instanceof Company)
				$this->_company = $company;
			else
				$this->getRequest()->redirect($this->createFrontUrl('site/invalidhost'), true, 302);
		}

//		$command = $this->db->createCommand('SET AUTOCOMMIT=0');
//		$command->execute();
	}

	public function createFrontUrl($route,$params=array(),$ampersand='&')
	{
		return 'http://'.$this->params['domain'].$this->createUrl($route, $params, $ampersand);
	}

	public function createCompanyUrl(Company $company,$route,$params=array(),$ampersand='&')
	{
		return 'http://'.$company->host.'.'.$this->params['domain'].$this->createUrl($route, $params, $ampersand);
	}

	public function getCompany()
	{
		return $this->_company;
	}
}