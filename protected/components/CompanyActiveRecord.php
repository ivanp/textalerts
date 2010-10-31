<?php

abstract class CompanyActiveRecord extends CActiveRecord
{
	/**
	 *
	 * @var Company
	 */
	public $company;
	
	static public $_static_company;

	public function __construct($scenario='insert')
	{
		if (self::$_static_company instanceof Company)
			$this->company = self::$_static_company;
		parent::__construct($scenario);
	}


	/**
	 * Base table name
	 */
	abstract public static function baseTableName();

	abstract public static function createSqlByCompany(Company $company);

	/**
	 * Get model based by Company
	 *
	 * @param Company $company
	 * @param string $className
	 * @return CompanyActiveRecord
	 */
	public static function modelByCompany(Company $company, $className = __CLASS__)
	{
		if (isset($company->id)) { // check if it's loaded
			$companyClassName = $className.'_'.$company->id;
			if (!class_exists($companyClassName, false))
			{
				$tableName = static::tableNameByCompany($company, static::baseTableName());
				$php = "class $companyClassName extends $className { public function tableName() { return '$tableName'; } }";
				eval($php);
				$companyClassName::$_static_company = $company;
			}
			$model = parent::model($companyClassName);
		}
		else
			$model = parent::model($className);
//		$model->company = $company;
		return $model;
	}

	/**
	 * Create a new record based by Company
	 * 
	 * @param Company $company
	 * @return CompanyActiveRecord
	 */
	public static function factoryByCompany(Company $company)
	{
		$model = static::modelByCompany($company);
		$className = get_class($model);
		$object = new $className;
		$object->company = $company;
		return $object;
	}

	/**
	 * Get table name based by Company
	 * 
	 * @param Company $company
	 * @param string $tableName
	 * @return string
	 */
	public static function tableNameByCompany(Company $company, $tableName)
	{
		return $company->id.'_'.$tableName;
	}

	public function getCompanyClass($className)
	{
		return get_class($className::modelByCompany($this->company));
	}

	public function tableName()
	{
		if ($this->company instanceof Company)
			$tblName = static::tableNameByCompany($this->company, static::baseTableName());
		else
			$tblName = static::baseTableName();
		return $tblName;
	}
}
