<?php $this->pageTitle=Yii::app()->name; ?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<div style="width: 100%; text-align: center">
<img src="<?php echo Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/images/front/testimonials.jpg')?>"/>
<br/><br/>
<img src="<?php echo Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/images/front/bigtext.jpg')?>"/>
</div>

<?php

//echo 'Guest='.var_export(Yii::app()->user->checkAccess('Guest'), true).'<br/>';
//echo 'Authenticated='.var_export(Yii::app()->user->checkAccess('Authenticated'), true).'<br/>';
////echo 'DoThis123='.var_export(Yii::app()->user->checkAccess('DoThis123'), true).'<br/>';
//
//$company = Company::model()->findByPk(1);
//$params = array('company' => $company);
////var_dump($params['company']->user_id);
//var_dump(Yii::app()->user->checkAccess('ManageOwnCompany', $params, false));
//var_dump(Yii::app()->user->checkAccess('ManageCompany', $params, false));
//var_dump(Yii::app()->user->checkAccess('SendMessageCompany', $params, false));
//
//$company = Company::model()->findByPk(1);
//foreach ($company->senders as $admin)
//{
//	echo 'Admin: '.$admin->email.'<br/>';
//}