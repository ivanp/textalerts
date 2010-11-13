<?php
$this->pageTitle=Yii::app()->name . ' - Create new company';
$this->breadcrumbs=array(
	'Companies' => array('company/index'),
	'Create new company',
);
?>
<h1>Create new company</h1>
<?php echo $this->renderPartial('_company_form', array('company'=>$company,'info'=>$info)); ?>