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

$this->portlets[] = array(
	'title' => 'Upcoming Events',
	'content' => $this->renderPartial('application.views.portlets.upcomingevents', array(), true)
);
?>
<h2><?php echo $this->company->name?></h2>

	<?php
	$label=$this->company->info->bb_label;
	if (empty($label))
		$label='Bulletin Board'; // default label

			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>$label,
			));
			echo $this->company->info->bb_text;
			$this->endWidget();
		?>

<div style="width:700px;margin: 0 auto;" id="fullcalendar"></div>

<div class="clear" style="height: 16px"></div>




<script>
<?php
ob_start();
?>
var offset=(new Date()).getTimezoneOffset();
var events_url='<?php echo $this->createAbsoluteUrl('/calendar/events')?>?offset='+offset;
$("#fullcalendar").fullCalendar({
	events: events_url,
	header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			}
});
<?php
$js=ob_get_contents();
ob_end_clean();?>
</script>
<?php
$cs=Yii::app()->getClientScript();
$cs->registerScript('calendar-index',$js,  CClientScript::POS_READY);

$cs->registerCssFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/css/fullcalendar.css'));
$cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/js/fullcalendar.min.js'));
?>
