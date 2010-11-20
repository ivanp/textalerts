<?php

class CompanyForm extends CActiveForm
{
	public function dateField($model,$attribute,$htmlOptions=array())
	{
		$id=CHtml::getIdByName(CHtml::resolveName($model,$attribute));
		$script='$("#'.$id.'").datepicker({dateFormat:"yy-mm-dd"});';
		$cs=Yii::app()->getClientScript();
		$cs->registerScript($id,$script,CClientScript::POS_READY);
		$htmlOptions=array_merge(
			array('size'=>10,'maxlength'=>10,'class'=>'datepick','readonly'=>'readonly'),
			$htmlOptions);
		if (is_numeric($model->$attribute))
			$htmlOptions['value']=date('Y-m-d');
		return CHtml::activeTextField($model,$attribute,$htmlOptions);
	}

	public function dateTimeField($model,$attribute,$htmlOptions=array(),$format='')
	{
		$id=CHtml::getIdByName(CHtml::resolveName($model,$attribute));
		$params=array('dateFormat'=>'yy-mm-dd','timeFormat'=>'hh:00','showMinute'=>false);
		$script='$("#'.$id.'").datetimepicker('.CJSON::encode($params).');';
		$cs=Yii::app()->getClientScript();
		$cs->registerScript($id,$script,CClientScript::POS_READY);
		$htmlOptions=array_merge(
			array('size'=>16,'maxlength'=>16,'readonly'=>'readonly'),
			$htmlOptions);
		if (is_numeric($model->$attribute))
			$htmlOptions['value']=date('Y-m-d H:00');
		return CHtml::activeTextField($model,$attribute,$htmlOptions);
	}
}
