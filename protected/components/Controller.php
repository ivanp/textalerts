<?php

class Controller extends CController
{
	public $menu=array();
	public $breadcrumbs=array();
	public $portlets=array();

	public function init()
	{
		parent::init();
		$cs = Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/js/app.js'), CClientScript::POS_HEAD);
	}

	public function createFrontUrl($route,$params=array(),$ampersand='&')
	{
		return Yii::app()->createFrontUrl($route,$params,$ampersand);
	}

	public function createCompanyUrl(Company $company,$route,$params=array(),$ampersand='&')
	{
		return Yii::app()->createCompanyUrl($company,$route,$params,$ampersand);
	}
}