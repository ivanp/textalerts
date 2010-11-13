<?php



$cs = Yii::app()->clientScript;

$cs->registerCoreScript('jquery');
$cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/js/app.js'), CClientScript::POS_HEAD);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<link rel="stylesheet" href="styles.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/datepicker.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/superfish.css" />
</head>

<body>
<div id="container">
	<div id="header">
    	<h1><a href="/">text alerts</a></h1>
        <h2>content management system</h2>
        <div class="clear"></div>
    </div>
    <div id="nav">
    	<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>$this->menu
		));

		?>
    </div>
    <div id="body">
			<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>isset($this->breadcrumbs) ? $this->breadcrumbs : array(),
	)); ?><!-- breadcrumbs -->
			<?php echo $content; ?>
        
    	<div class="clear"></div>
    </div>
</div>
<div id="footer">
    <div class="footer-width footer-bottom">
        <p>&copy; YourSite 2010. Design by <a href="http://www.spyka.net">Free CSS Templates</a> | <a href="http://www.justfreetemplates.com">Free Web Templates</a></p>
     </div>

</div>
</body>
</html>
