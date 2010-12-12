<?php

class CompanyForm extends CActiveForm
{
	const DateFormat='mm/dd/yy'; //'yy-mm-dd';
	const TimeFormat='hh:mm tt';
	
	public function dateField($model,$attribute,$htmlOptions=array())
	{
		$id=isset($htmlOptions['id']) ? $htmlOptions['id']
			: CHtml::getIdByName(CHtml::resolveName($model,$attribute));
		$script='$("#'.$id.'").datepicker('.CJSON::encode(array('dateFormat'=>self::DateFormat)).');';
		$cs=Yii::app()->getClientScript();
		$cs->registerScript($id,$script,CClientScript::POS_READY);
		$htmlOptions=array_merge(
			array('size'=>10,'maxlength'=>10,'class'=>'datepick','readonly'=>'readonly'),
			$htmlOptions);
		if (is_numeric($model->$attribute))
			$htmlOptions['value']=date('m/d/Y',$model->$attribute);
		return CHtml::activeTextField($model,$attribute,$htmlOptions);
	}

	public function timeField($model,$attribute,$htmlOptions=array(),$format='')
	{
//		var_dump($model->$attribute);
		$id=isset($htmlOptions['id']) ? $htmlOptions['id']
			: CHtml::getIdByName(CHtml::resolveName($model,$attribute));
		$params=array('timeFormat'=>self::TimeFormat,'showMinute'=>true,
				'ampm'=>true,'stepMinute'=>15);
		$script='$("#'.$id.'").timepicker('.CJSON::encode($params).');';
		$cs=Yii::app()->getClientScript();
		$cs->registerScript($id,$script,CClientScript::POS_READY);
		$htmlOptions=array_merge(
			array('size'=>8,'maxlength'=>8,'readonly'=>'readonly'),
			$htmlOptions);
		if (is_numeric($model->$attribute))
			$htmlOptions['value']=date('h:00 a',$model->$attribute);
		return CHtml::activeTextField($model,$attribute,$htmlOptions);
	}

	public function dateTimeField($model,$attribute,$htmlOptions=array(),$format='')
	{
		$id=isset($htmlOptions['id']) ? $htmlOptions['id']
			: CHtml::getIdByName(CHtml::resolveName($model,$attribute));
		$params=array('dateFormat'=>self::DateFormat,'timeFormat'=>self::TimeFormat,
			'showMinute'=>true,'ampm'=>true,'stepMinute'=>15);
		$script='$("#'.$id.'").datetimepicker('.CJSON::encode($params).');';
		$cs=Yii::app()->getClientScript();
		$cs->registerScript($id,$script,CClientScript::POS_READY);
		$htmlOptions=array_merge(
			array('size'=>19,'maxlength'=>19,'readonly'=>'readonly'),
			$htmlOptions);
		if (is_numeric($model->$attribute))
			$htmlOptions['value']=date('m/d/Y h:00 a',$model->$attribute);
		return CHtml::activeTextField($model,$attribute,$htmlOptions);
	}
}
