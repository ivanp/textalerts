<?php
$this->layout='//layouts/column2';
$this->pageTitle=$this->company->name;
$this->breadcrumbs=array();

//$cs = Yii::app()->clientScript;
//$cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/js/jquery-ui-1.8.5.custom.min.js'), CClientScript::POS_HEAD);
if (Yii::app()->user->isGuest)
{
	$this->portlets[] = array(
		'title' => 'Login',
		'content' => $this->renderPartial('_loginbox', array(), true)
	);
}
?>
<h2><?php echo $this->company->name?></h2>

<?php
$this->widget('application.extensions.fullcalendar.FullcalendarGraphWidget',
    array(
        'data'=>array(
                'title'=> 'All Day Event',
                'start'=> date('Y-m-j')
        ),
        'options'=>array(
            'editable'=>false,
        ),
        'htmlOptions'=>array(
               'style'=>'width:700px;margin: 0 auto;'
        ),
    )
);
?>

<!--<h3>Groups:</h3>
<ul>
	<?php foreach ($this->company->getGroups() as $group): ?>
	<li><?php echo CHtml::link($group->title, $group->createViewUrl())?></li>
	<?php endforeach; ?>
</ul>-->