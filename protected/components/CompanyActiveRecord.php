<?php

abstract class CompanyActiveRecord extends CActiveRecord
{
	/**
	 *
	 * @var Company
	 */
	private $_company;


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
		$companyClassName = $className.'_'.$company->id;
		if (!class_exists($companyClassName, false))
		{
			$tableName = static::tableNameByCompany($company, static::baseTableName());
			$php = "class $companyClassName extends $className { public function tableName() { return '$tableName'; } }";
			eval($php);
		}
		$model = parent::model($companyClassName);
		$model->_company = $company;
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
		$object->_company = $company;
		return $object;
	}

	public function getCompany()
	{
		return $this->_company;
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

}
