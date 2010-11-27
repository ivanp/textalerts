<?php
$cs=Yii::app()->getClientScript();

$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/js/superfish.js'), CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/js/app.js'), CClientScript::POS_HEAD);

$company=$this->company;

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/datepicker.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/superfish.css" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<script type="text/javascript">
		$(document).ready(function() {
			jQuery(function(){
				jQuery('#mainmenu ul').superfish();
			});

			if (App.modules.company.<?php echo $this->uniqueid?>) {
				if (App.modules.company.<?php echo $this->uniqueid?>.init &&
					typeof(App.modules.company.<?php echo $this->uniqueid?>.init) == 'function') {
					App.modules.company.<?php echo $this->uniqueid?>.init();
				}

				if (App.modules.company.<?php echo $this->uniqueid?>.<?php echo $this->action->id?> &&
					typeof(App.modules.company.<?php echo $this->uniqueid?>.<?php echo $this->action->id?>) == 'function') {
					App.modules.company.<?php echo $this->uniqueid?>.<?php echo $this->action->id?>();
				}
			}
				
		});
	</script>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><a href="<?php echo $this->createUrl('site/index'); ?>"><?php
		$logo=$company->getLogoUrl();
		$heading=htmlentities($this->company->info->heading);
		if ($logo!==false) {
			echo CHtml::image($logo,$heading);
		} else {
			echo $heading;
		} ?></a></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>isset($this->menu) ? $this->menu : array(),
			'htmlOptions'=>array('class'=>'sf-menu')
		)); 
		
		?>
	</div><!-- mainmenu -->
	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>isset($this->breadcrumbs) ? $this->breadcrumbs : array(),
	)); ?><!-- breadcrumbs -->

	<?php echo $content; ?>

	<div id="footer">
		Copyright &copy; 2010 mychurchalerts.com
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>