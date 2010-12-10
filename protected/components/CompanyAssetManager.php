<?php

class CompanyAssetManager extends CAssetManager
{
	protected function hash($path)
	{
		return sprintf('%x',crc32($path.Yii::getVersion().Yii::app()->params['version']));
	}
}