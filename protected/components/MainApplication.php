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
	private $_company = null;

    //put your code here
	static public function create($config) {
		return Yii::createApplication('MainApplication',$config);
	}

	public function getCompany() {
		return $this->_company;
	}

	public function isCompanySelected() {
		return ($this->_company instanceof Company);
	}

	public function init() {
		parent::init();
		$company = Company::model()->find('host = :host', array(':host' => $_SERVER['SERVER_NAME']));
		if ($company instanceof Company) {
			$this->_company = $company;
		}
	}
}