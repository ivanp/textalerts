<?php
define('HOSTNAME_MAIN', 'textalert.local');
define('HOSTNAME_ADMIN', 'admin.textalert.local');

// Yii main script
$yii=dirname(__FILE__).'/../yii-1.1.4.r2429/framework/yii.php';
switch ($_SERVER['SERVER_NAME'])
{
	case HOSTNAME_MAIN:
		define('CONTROLLER_MODE', 'front');
		break;
	case HOSTNAME_ADMIN:
		define('CONTROLLER_MODE', 'admin');
		break;
	default:
		define('CONTROLLER_MODE', 'company');
		break;;
}

// Configuration differs for each mode
$config=dirname(__FILE__).'/protected/config/'.CONTROLLER_MODE.'.php';
// Extended application
$app=dirname(__FILE__).'/protected/components/MainApplication.php';
// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
// Load Yii & application
require_once($yii);
require_once($app);
// Run application
MainApplication::create($config)->runEnd(CONTROLLER_MODE);
