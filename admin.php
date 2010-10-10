<?php
define('ADMIN_HOSTNAME', 'admin.textalert.com');



$yii=dirname(__FILE__).'/../yii-1.1.4.r2429/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';
// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
require_once($yii);
$app = Yii::createWebApplication($config);
if ($_SERVER['SERVER_NAME'] != ADMIN_HOSTNAME)
{
	throw new CHttpException(404, Yii::t('yii','Unable to resolve the request "admin.php".'));
}

$app->run();
